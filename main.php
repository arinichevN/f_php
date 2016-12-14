<?php

$app_version = 2;
$basePath = '';
$name = '';
$captcha = '';
$uds_path = "";
$db_conninfo = "";
$udp_addr = '';

function go($appDir) {
    global $basePath, $name, $captcha, $uds_path, $db_conninfo, $udp_addr;
    $basePath = $appDir;
    if (is_string($appDir)) {
        $c = require($basePath . DIRECTORY_SEPARATOR . 'config/main.php');
    }
    if (isset($c['name'])) {
        $name = $c['name'];
        unset($c['name']);
    }
    if (isset($c['db']['use'])) {
        require 'include' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . $c['db']['use'] . '.php';
        if (isset($c['db']['conninfo'])) {
            $db_conninfo = $c['db']['conninfo'];
        }
        \db\init();
        unset($c['db']);
    }
    if (isset($c['udp']['use'])) {
        require 'include' . DIRECTORY_SEPARATOR . 'uds' . DIRECTORY_SEPARATOR . $c['uds']['use'] . '.php';
        if (isset($c['udp']['port']) && isset($c['udp']['addr'])) {
            $udp_addr = "udp://" . $c['udp']['addr'] . ":" . $c['udp']['port'];
        }
        unset($c['udp']);
    }
    if (isset($c['udp']['use'])) {
        require 'include' . DIRECTORY_SEPARATOR . 'udp' . DIRECTORY_SEPARATOR . $c['udp']['use'] . '.php';
        $uds_path = DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $name . '.sock';
        // $uds_path = DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR .'user1'. DIRECTORY_SEPARATOR. $name . '.sock';
        unset($c['uds']);
    }
    if (isset($c['session']['use'])) {
        require 'include' . DIRECTORY_SEPARATOR . 'session' . DIRECTORY_SEPARATOR . $c['session']['use'] . '.php';
        unset($c['session']);
    }
    if (isset($c['check']['use'])) {
        foreach ($c['check']['use'] as $value) {
            require 'include' . DIRECTORY_SEPARATOR . 'check' . DIRECTORY_SEPARATOR . $value . '.php';
        }
        unset($c['check']);
    }
    if (isset($c['captcha']['use'])) {
        $captcha = $c['captcha']['use'];
        unset($c['captcha']);
    }

    spl_autoload_register('autoload');
    run();
}

function autoload($class) {
    global$basePath;
    $p = $basePath . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'util' . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';
    if (file_exists($p)) {
        require $p;
    } else {

        throw new \Exception('no class');
    }
}

function loadCaptcha() {
    global $captcha;
    require 'include' . DIRECTORY_SEPARATOR . 'captcha' . DIRECTORY_SEPARATOR . $captcha . '.php';
}

function run() {
    global $_su, $db_connection, $uds;
    try {
        \session\start();
        $raw_request = file_get_contents("php://input");
        if ($raw_request !== false) {
            if ($raw_request === '') {
                $response = getClientApp($_su);
                send($response, 'html');
            } else {
                $request = json_decode($raw_request, true, 10);
                if (!is_null($request)) {
                    processRequestM($request);
                } else {
                    $response = getClientApp($_su);
                    send($response, 'html');
                }
            }
        }
    } catch (\Exception $exc) {
        $response = [
            'c_status' => 2,
            'message' => $exc->getMessage()
        ];
        $code = $exc->getCode();
        if ($code === 3) {
            $response['c_status'] = 3;
        }
        send($response, 'json');
    }
    if ($db_connection) {
        \db\suspend();
    }
    if ($uds) {
        \uds\suspend();
    }
    if ($udp) {
        \udp\suspend();
    }
}

function getClientApp($app) {
    global $basePath, $name;
    ob_start();
    ob_implicit_flush(false);
    require($basePath . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . $app . '.php');
    return ob_get_clean();
}

function send($content, $type) {
    switch ($type) {
        case 'html':
            header('Content-Type:text/html');
            echo $content;
            break;
        case 'json':
            header('Content-Type:application/json; charset=UTF-8');
            echo json_encode($content);
            break;
    }
}

function checkParam(&$data) {
    $b = true;
    foreach ($data as $k => $v) {
        if (is_array($v)) {
            $b = $b && checkParam($v);
        } else {
            if (preg_match_all("/^[\w-,. ]+$/u", $v) === 1 or is_null($v)) {
                $b = $b && true;
            } else {
                $b = $b && false;
            }
        }
    }
    return $b;
}

function checkPermission($user_id, $ptype) {
    $query = "SELECT check_user_permission ($user_id , $ptype) AS ok;";
    $result = getData($query);
    $row = $result->fetch_assoc();
    if ($row['ok'] === '1') {
        return true;
    }
    return false;
}

function getClassPath(&$arr) {
    $output = '';
    foreach ($arr as $v) {
        if (!is_string($v)) {
            throw new \Exception('check param');
        }
        $output.='\\';
        $output.=$v;
    }
    return substr($output, 1);
}

function importAction($class) {
    global $basePath;
    $p = $basePath . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'action' . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';
    if (file_exists($p)) {
        require_once $p;
    } else {
        throw new \Exception('no action');
    }
}

function authorize(&$u) {
    global $_su, $_ur, $captcha;
    $m = '';
    foreach ($u as $k => $v) {
        if ($k === $_su) {
            $m = $v;
        }
    }
    switch ($m) {
        case '*':
            break;
        case '':
            throw new \Exception('restricted');
        case 'captcha':
            require 'captcha/' . $captcha . '.php';
            if (!\captcha\utilize()) {
                throw new \Exception('restricted');
            }
            break;
    }
    if ($_ur === 'new_user') {
        throw new \Exception('update your data', 3);
    }
}

function processRequest(&$r) {
    $c = getClassPath($r['action']);
    importAction($c);
    if (method_exists($c, 'getUser')) {
        authorize($c::getUser());
    }
    if (isset($r['param'])) {
        $response = $c::execute($r['param']);
    } else {
        $response = $c::execute();
    }
    $response['status'] = 'done';
    send($response, 'json');
}

function processRequestM(&$r) {
    $response = [
        'data' => [],
        'status' => [],
        'c_status' => 1
    ];
    if (!is_array($r)) {
        throw new \Exception('check param');
    }
    foreach ($r as $a) {
        if (!isset($a['action']) || !is_array($a['action'])) {
            throw new \Exception('check param');
        }
        $c = getClassPath($a['action']);
        importAction($c);
        if (method_exists($c, 'getUser')) {
            authorize($c::getUser());
        }
        authorize($c::getUser());
        try {
            if (isset($a['param'])) {
                $ri = $c::execute($a['param']);
            } else {
                $ri = $c::execute();
            }
            $response['data'][] = $ri;
            $response['status'][] = 1;
        } catch (\Exception $exc) {
            $response['data'][] = $exc->getMessage();
            $response['status'][] = 0;
            $response['c_status'] = 0;
        }
    }
    send($response, 'json');
}

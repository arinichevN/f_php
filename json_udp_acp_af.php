<?php

$app_version = 2;
$basePath = '';
$name = '';

function go($appDir) {
    global $basePath, $name;
    $basePath = $appDir;
    if (is_string($appDir)) {
        require($basePath . DIRECTORY_SEPARATOR . 'config/main.php');
        $c = f_getConfig();
    }
    if (isset($c['name'])) {
        $name = $c['name'];
        unset($c['name']);
    }

    if (isset($c['sock']['use'])) {
        require 'include' . DIRECTORY_SEPARATOR . 'sock' . DIRECTORY_SEPARATOR . $c['sock']['use'] . '.php';
        if (isset($c['sock']['port']) && isset($c['sock']['addr']) && isset($c['sock']['timeout'])) {
            try {
                \sock\init($c['sock']['addr'], $c['sock']['port'], $c['sock']['timeout']);
            } catch (\Exception $exc) {
                $response = [
                    'c_status' => 2,
                    'message' => $exc->getMessage()
                ];
                $code = $exc->getCode();
                if ($code === 3) {
                    $response['c_status'] = 3;
                }
                send($response);
                return;
            }
        }
        unset($c['sock']);
    }
    if (isset($c['acp']['use'])) {
        require 'include' . DIRECTORY_SEPARATOR . 'acp' . DIRECTORY_SEPARATOR . $c['acp']['use'] . '.php';
        unset($c['acp']);
    }
    if (isset($c['check']['use'])) {
        foreach ($c['check']['use'] as $value) {
            require 'include' . DIRECTORY_SEPARATOR . 'check' . DIRECTORY_SEPARATOR . $value . '.php';
        }
        unset($c['check']);
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

function run() {
    global $sock;
    try {
        $raw_request = file_get_contents("php://input");
        if ($raw_request !== false) {
            if ($raw_request === '') {
                throw new \Exception('no request');
            } else {
                $request = json_decode($raw_request, true, 10);
                if (!is_null($request)) {
                    processRequest($request);
                } else {
                    throw new \Exception('failed to parse request');
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
        send($response);
    }
    if ($sock) {
        \sock\suspend();
    }
}

function send($content) {
    header('Content-Type:application/json; charset=UTF-8');
    echo json_encode($content);
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

function processRequest(&$r) {
	global $basePath;
    $response = [
        'data' => [],
        'status' => [],
        'c_status' => 1
    ];
    if (!is_array($r)) {
        throw new \Exception('check param: array expected');
    }
    foreach ($r as $a) {
        if (!isset($a['action']) || !is_array($a['action'])) {
            throw new \Exception('check param: bad action');
        }
        try {
	        $c = getClassPath($a['action']);
		    $path = $basePath . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'action' . DIRECTORY_SEPARATOR . str_replace('\\', '/', $c) . '.php';
		    if (!file_exists($path)) {
		        throw new \Exception("no action: $path");
		    }
		    include $path;
            if (isset($a['param'])) {
                $out = $af($a['param']);
            } else {
                $out = $af();
            }
            $response['data'][] = $out;
            $response['status'][] = 1;
        } catch (\Exception $exc) {
            $response['data'][] = $exc->getMessage();
            $response['status'][] = 0;
            $response['c_status'] = 0;
        }
    }
    send($response);
}

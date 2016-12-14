<?php

$app_version = 2;
$basePath = '';
$name = '';

function go($appDir) {
    global $basePath, $name;
    $basePath = $appDir;
    if (is_string($appDir)) {
        $c = require($basePath . DIRECTORY_SEPARATOR . 'config/main.php');
    }
    if (isset($c['name'])) {
        $name = $c['name'];
        unset($c['name']);
    }
    run();
}

function run() {
    try {
        $response = getClientApp("main");
        send($response);
    } catch (\Exception $exc) {
        header("HTTP/1.0 404 Not Found");
        echo '';
    }
}

function getClientApp($app) {
    global $basePath, $name;
    ob_start();
    ob_implicit_flush(false);
    require($basePath . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR . $app . '.php');
    return ob_get_clean();
}

function send($content) {
    header('Content-Type:text/html');
    echo $content;
}


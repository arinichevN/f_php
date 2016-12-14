<?php

$db_connection;

function init($host, $user, $password, $dbName) {
    global $db_connection;
    $db_connection = new mysqli($host, $user, $password, $dbName);
    if ($db_connection->connect_errno) {
        throw new Exception("Connection to database failed");
    }
    $pre_query = "SET NAMES utf8";
    getData($pre_query);
}

/**
 * Call it after you have asked database the last time
 * @return void
 */
function suspend() {
    global $db_connection;
    $db_connection->close();
    $db_connection = null;
}

function getData($query) {
    global $db_connection;
    $result = $db_connection->query($query);
    if ($result) {
        return $result;
    } else {
        throw new Exception("query failed: " . $query . 'info: ' . $db_connection->error . 'code: ' . $db_connection->errno);
    }
}

function getDataP($query) {
    $result = getData($query);
    if ($result === true) {
        throw new Exception('sql error: returned nothing');
    }
    $row = $result->fetch_assoc();
    if (isset($row['status']) && isset($row['message'])) {
        switch ($row['status']) {
            case 'done':
                return ['message' => $row['message']];
                break;
            case 'error':
                throw new Exception($row['message']);
                break;
        }
    } else {
        throw new Exception('getDataP failed');
    }
}

function test() {
    global $db_connection;
    $query = "SELECT * FROM `link` WHERE `statement_id`=1";
    $result = $db_connection->query($query);
    $row = $result->fetch_assoc();
    if (is_null($row)) {
        echo 'nulllll';
    } else {
        echo 'not null';
    }
    var_dump($row);
}

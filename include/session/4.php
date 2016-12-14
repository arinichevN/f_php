<?php

//new user || old user session
//used when we need to force new user to update his data, while old user can do any action
//saves user id in file 

namespace session;

$_su; //user name
$_ur; //user role

const TIMEOUT = 3600;

function start() {
    global $_su;
    global $_ur;
    $_su = "stranger";
    if (isset($_COOKIE['u'])) {
        if (checkUid($_COOKIE['u'])) {
            if (idExists($_COOKIE['u'])) {
                $_ur = "old_user";
                return;
            }
        }
    }
    $v = getId();
    saveId($v);
    setcookie("u", $v);
    $_ur = "new_user";
}

function idExists($id) {
    global $name;
    $handle = @fopen("/tmp/" . $name . "_session.txt", "r");
    if ($handle === FALSE) {
        return false;
    }
    $buffer = fgets($handle);
    if ($buffer == false) {
        return false;
    }
    if (feof($handle)) {
        return false;
    }
    fclose($handle);
    $buffer = (int) $buffer;
    $id = (int) $id;
    if ($buffer !== $id) {
        return false;
    }
    return true;
}

function saveId($id) {
    global $name;
    file_put_contents("/tmp/" . $name . "_session.txt", $id . "\n");
}

function getId() {
    $v1 = (string) rand(0, getrandmax());
    $v2 = (string) rand(0, getrandmax());
    return $v1 . $v2;
}

function checkUid($v) {
    if (preg_match_all('/^[0-9]+$/', $v) === 1) {
        return true;
    }
    return false;
}

<?php

//new user || old user session
//used when we need to force new user to update his data, while old user can do any action

namespace session;

$_su; //user name
$_ur; //user role

const TIMEOUT = 3600;

function start() {
    global $_su, $name;
    global $_ur;
    $_su = "stranger";
    $_ur = "old_user";
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

<?php

//new user || old user session
//used when we need to force new user to update his data, while old user can do any action
//saves user id in db

namespace session;

$_su; //user name
$_ur; //user role

const TIMEOUT = 3600;

function start() {
    global $_su, $name;
    global $_ur;
    $_su = "stranger";
    if (isset($_COOKIE['u'])) {
        if (checkUid($_COOKIE['u'])) {
            $q = "select count(*) from $name.session where id like '{$_COOKIE['u']}'";
            $c = \db\getCount($q);
            if ($c === 1) {
                $_ur = "old_user";
                return;
            }
        }
    }
    $v = getId();
    $q = "update $name.session set id='$v'";
    $ar = \db\getDataM($q);
    if ($ar < 1) {
        $q = "insert into $name.session values ('$v')";
        $ar = \db\getDataM($q);
    }
    setcookie("u", $v);
    $_ur = "new_user";
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

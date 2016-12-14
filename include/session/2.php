<?php

namespace session;

$_su;

const TIMEOUT = 3600;

function start() {
    global $_su;
    if (isset($_GET['u']) && check_getu($_GET['u'])) {
        $_su = 'owner_login';
        update_ol();
    } else {
        if (isset($_COOKIE['id']) && check_id()) {
            switch (get_status()) {
                case 'w':
                    if (isset($_POST['p']) && check_pass($_POST['p'])) {
                        $_su = 'owner';
                        switch_to_o();
                        update_o();
                    } else {
                        $_su = 'stranger';
                    }
                    break;
                case 'l':
                    $_su = 'owner';
                    update_o();
                    break;
            }
        } else {
            $_su = 'stranger';
        }
    }
}

function check_getu(&$v) {
    $t = sha1($v);
    $q = "select count(*) from session.owner where login='{$t}'";
    $c = \db\getCount($q);
    if ($c === 1) {
        return true;
    }
    return false;
}

function check_id() {
    $v = \db\escape_literal($_COOKIE['id']);
    $t = time() - TIMEOUT;
    $q = "select count(*) from session.owner where sid=$v and stimeout>$t";
    $c = \db\getCount($q);
    if ($c === 1) {
        return true;
    }
    return false;
}

function check_pass(&$v) {
    $v1 = sha1($v);
    $q = "select count(*) from session.owner where pass='$v1'";
    $c = \db\getCount($q);
    if ($c === 1) {
        return true;
    }
    return false;
}

function get_status() {
    $q = "select state from session.owner where id=1";
    $r = \db\getRow($q);
    return $r['state'];
}

function update_ol() {
    $t = time() + TIMEOUT;
    $v = sha1(rand());
    setcookie("id", $v, $t);
    $q = "update session.owner set sid='$v', stimeout=$t, state='w' where id=1";
    \db\getDataM($q);
}

function update_o() {
    $t = time() + TIMEOUT;
    $v = sha1(rand());
    setcookie("id", $v, $t);
    $q = "update session.owner set sid='$v', stimeout=$t where id=1";
    \db\getDataM($q);
}

function switch_to_o() {
    $q = "update session.owner set state='l' where id=1";
    \db\getDataM($q);
}

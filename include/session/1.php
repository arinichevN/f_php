<?php

namespace session;

$_su;

function start() {
    delete_expired();
    if (isset($_GET['u']) && is_sha1($_GET['u'])) {
        check_getu();
    } else {
        if (isset($_COOKIE['id']) && is_sha1($_COOKIE['id'])) {
            check_cookieid();
        } else {
            $_su = 's';
        }
    }
    update_cookie($_su);
}

function check_getu() {
    global $_su;
    $q = "select count(*) from owner where login='{$_GET['u']}'";
    $c = \db\getCount($q);
    if ($c === 1) {
        $_su = 'w'; //waiting for password
    }
}

function check_cookieid() {
    global $_su;
    $q = "select * from session where id='{$_COOKIE['id']}'";
    $r = \db\query($q);
    if ($r) {
        $row = \db\fetch_assoc($r);
        $_su = $row['role'];
        switch ($row['role']) {
            case 'o'://owner
                if ($row['timeout'] < time()) {
                    $_su = 's';
                } else {
                    $_su = 'o';
                }
                break;
            case 'w'://waiting for password
                if (isset($_POST['p']) && is_p($_POST['p'])) {
                    $q = "select count(*) from owner where password='{$_POST['p']}'";
                    $c = \db\getCount($q);
                    if ($c === 1) {
                        $_su = 'o';
                    } else {
                        $_su = 's';
                    }
                } else {
                    $_su = 's';
                }
                break;
            case 's'://stranger
                $_su = 's';
                break;
        }
    } else {
        $_su = 's';
    }
}

function is_sha1(&$v) {
    if (preg_match_all('/^[A-Za-z0-9]{40}$/', $v) === 1) {
        return true;
    }
    return false;
}

function is_p(&$v) {
    if (preg_match_all('/^[0-9]{4}$/', $v) === 1) {
        return true;
    }
    return false;
}

function update_cookie($role) {
    global $_su;
    $t = time() + 3600;
    do {
        $v = sha1(rand());
        $q = "select count(*) from session where id='$v'";
        $c = \db\getCount($q);
    } while ($c === 1);
    setcookie("id", $v, $t);
    $q = "insert into session (id,role,timeout) values ('{$v}','{$role}',{$t})";
    \db\getDataM($q);
}

function delete_expired() {
    $t = time();
    $q = "delete from session where timeout < $t and role='o'";
    \db\query($q);
}

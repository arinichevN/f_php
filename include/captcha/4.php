<?php

/**
 * user must select class for each image
 * db:answer(id(pk),value,timeout,status)
 * @author <arinichev_n@mail.ru>
 * 
 */

namespace captcha;
const TOUTSEC = 120;
const IMGNUM = 5;
const IMGCLASSNUM = 5;

function init() {
    $answer = null;
    $status = null; //n-new session, we can only send a puzzle; o-old session, we have already sent him a puzzle, now we can validate his answer; d - do it! he has guessed our puzzle, now he can waste his ability 
    if (isset($_COOKIE['cid']) && \check\_sha1($_COOKIE['cid'])) {
        $q = "delete from captcha.answer where timeout < (extract(epoch from (select current_timestamp))::bigint-" . TOUTSEC . ");";
        \db\getDataM($q);
        $q = "select value, status from captcha.answer where id='{$_COOKIE['cid']}'";
        $row = \db\getRow($q);
        if ($row) {
            $answer = $row['value'];
            if ($row['status'] === 't') {
                $status = 'd';
            } else {
                $status = 'o';
            }
        }
    }
    return ['status' => $status, 'answer' => $answer];
}

function makeImages($images) {
    global $basePath;
    $images_data = [];
    foreach ($images as $v) {
        $f = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . $v[0] . DIRECTORY_SEPARATOR . $v[1] . '.jpg';
        if (file_exists($f)) {
            $i = imagecreatefromjpeg($f);
            $r = rand(1, 255);
            $g = rand(1, 255);
            $b = rand(1, 255);
            $c = rand(1, 30);
            $p = rand(1, 3);
            imagefilter($i, IMG_FILTER_COLORIZE, $r, $g, $b, 100);
            imagefilter($i, IMG_FILTER_CONTRAST, $c);
            imagefilter($i, IMG_FILTER_PIXELATE, $p);
            $ni = imagescale($i, 200, 150, IMG_BICUBIC);
            ob_start();
            imagejpeg($ni);
            $data = ob_get_contents();
            ob_end_clean();
            $images_data[] = base64_encode($data);
            imagedestroy($i);
            imagedestroy($ni);
        } else {
            throw new \Exception('image file not found' . $f);
        }
    }
    return $images_data;
}

function rmdots(&$a) {
    while ($a[0] === '.' || $a[0] === '..') {
        array_shift($a);
    }
}

function getRandImgs($img_num, $classes) {
    $imgs1 = []; //random image numbers
    $imgs = []; //output ['image_dir_name','image_file_name']
    $class_num = count($classes);
    for ($i = 0; $i < $img_num; $i++) {
        $imgs1[] = rand(1, $class_num * IMGCLASSNUM);
    }

    foreach ($imgs1 as $v) {
        $class = $classes[ceil($v / IMGCLASSNUM) - 1];
        $img = $v % IMGCLASSNUM + 1;
        $imgs[] = [$class, $img];
    }
    return $imgs;
}

function getAllClasses() {
    if ($classes = scandir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'image')) {
        sort($classes);
        rmdots($classes);
        return $classes;
    } else {
        throw new \Exception('cannot scandir');
    }
}

function getAnswer($images, $classes) {
    $answer1 = [];
    for ($i = 0; $i < count($images); $i++) {
        $answer1[] = $images[$i][0];
    }
    for ($i = 0; $i < count($answer1); $i++) {
        $answer1[$i] = array_search($answer1[$i], $classes,true);
    }
    return implode('_', $answer1);
}

function getPuzzle() {
    $classes = getAllClasses();
    $images = getRandImgs(IMGNUM, $classes);
    do {
        $cid = sha1(rand());
        $q = "select count(*) from captcha.answer where id='$cid'";
        $c = \db\getCount($q);
    } while ($c !== 0);
    $timeout = time() + TOUTSEC;
    $answer = getAnswer($images, $classes);
    $q = "insert into captcha.answer (id,value,timeout) values ('{$cid}','{$answer}',{$timeout})";
    \db\getDataM($q);
    $img_data = makeImages($images);
    if (!setcookie('cid', $cid)) {
        throw new \Exception('set cookie failed');
    }
    return [
            'image' => $img_data,
            'clas' => $classes,
            'timeout' => $timeout,
        'duration'=>  TOUTSEC
    ];
}

function utilize() {
    $ini = init();
    if ($ini['status'] === 'd') {
        $q = "delete from captcha.answer where id='{$_COOKIE['cid']}'";
        \db\getDataM($q);
        return true;
    }
    return false;
}

function validate($p) {
    $ini = init();
    if (isset($p['answer']) && is_array($p['answer'])) {
        $t = implode('_', $p['answer']);
        if ($ini['status'] === 'o' && $ini['answer'] === $t) {
            $q = "update captcha.answer set status='t' where id='{$_COOKIE['cid']}'";
            \db\getDataM($q);
            return true;
        } else {
            $q = "delete from captcha.answer where id='{$_COOKIE['cid']}'";
            \db\getDataM($q);
        }
    }
    return false;
}

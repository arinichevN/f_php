<?php

/**
 * user must select images of certain class
 * db:image(id(pk)),class(id(pk),name),image_class(image_id(fk),class_id(fk)),answer(id(pk),value,timeout,status)
 * @author <arinichev_n@mail.ru>
 * 
 */

namespace captcha;

const TOUTSEC = 60;
const IMGNUM = 10;

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
        $f = $basePath . '/include/util/captcha/image/' . $v[0] . '.jpg';
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
            throw new \Exception('image file not found');
        }
    }
    return $images_data;
}

function getPuzzle() {
    $img = [];
    $q = "select * from captcha.image_class order by random() limit " . IMGNUM;
    $r = \db\getData($q);
    while ($row = \db\fetch_assoc($r)) {
        array_push($img, [$row['image_id'], $row['class_id']]);
    }
    if (count($img) !== IMGNUM) {

        throw new \Exception("captcha: add images");
    }
    do {
        $cid = sha1(rand());
        $q = "select count(*) from captcha.answer where id='$cid'";
        $c = \db\getCount($q);
    } while ($c !== 0);
    $timeout = time() + TOUTSEC;
    $cr = $img[rand(0, count($img) - 1)][1]; //current class id
    $q = "select name from captcha.class where id=$cr";
    $r = \db\getData($q);
    $row = \db\fetch_assoc($r);
    $query = $row['name'];
    $answer1 = [];
    for ($i = 0; $i < count($img); $i++) {
        if ($img[$i][1] === $cr) {
            $answer1[] = $i;
        }
    }
    $answer = implode('_', $answer1);
    $q = "insert into captcha.answer (id,value,timeout) values ('{$cid}','{$answer}',{$timeout})";
    \db\getDataM($q);
    $img_data = makeImages($img);
    if (!setcookie('cid', $cid)) {
        throw new \Exception('set cookie failed');
    }
    return [
        'message' => 'captcha',
        'param' => [
            'image' => $img_data,
            'find' => $query,
            'timeout' => $timeout
        ]
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
        }
    }
    return false;
}

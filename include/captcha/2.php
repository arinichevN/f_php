<?php

/**
 * user must select class for each image
 * db:image(id(pk),class_id(fk)), class(id(pk),name), answer(id(pk),value,timeout,status)
 * @author <arinichev_n@mail.ru>
 * 
 */

namespace captcha;

class base {

    const TOUTSEC = 60;
    const IMGNUM = 10;

    private $images = [];
    private $images_data = [];
    private $classes = [];
    private $answer = '';
    private $status = 'n'; //n-new session, we can only send a puzzle; o-old session, we have already sent him a puzzle, now we can validate his answer; d - do it! he has guessed our puzzle, now he can waste his ability 

    public function __construct($p = null) {
        if (isset($_COOKIE['cid']) && \check\_sha1($_COOKIE['cid'])) {
            $q = "delete from captcha.answer where timeout < (extract(epoch from (select current_timestamp))::bigint-" . self::TOUTSEC . ");";
            \db\getDataM($q);
            $q = "select value, status from captcha.answer where id='{$_COOKIE['cid']}'";
            $row = \db\getRow($q);
            if ($row) {
                $this->answer = $row['value'];
                if ($row['status'] === 't') {
                    $this->status = 'd';
                } else {
                    $this->status = 'o';
                }
            }
        }
    }

    public function utilize() {
        if ($this->status === 'd') {
            $q = "delete from captcha.answer where id='{$_COOKIE['cid']}'";
            \db\getDataM($q);
            return true;
        }
        return false;
    }

    public function validate($p) {
        if (isset($p['answer']) && is_array($p['answer'])) {
            $t = implode('_', $p['answer']);
            if ($this->status === 'o' && $this->answer === $t) {
                $q = "update captcha.answer set status='t' where id='{$_COOKIE['cid']}'";
                \db\getDataM($q);
                $this->status = 'd';
                return true;
            }
        }
        return false;
    }

    public function getPuzzle() {
        $q = "select * from captcha.image order by random() limit " . self::IMGNUM;
        $r = \db\getData($q);
        while ($row = \db\fetch_assoc($r)) {
            array_push($this->images, [$row['id'], $row['class_id']]);
        }
        if (count($this->images) !== self::IMGNUM) {
            throw new \Exception("captcha: add images");
        }
        $q = "select * from captcha.class";
        $r = \db\getData($q);
        while ($row = \db\fetch_assoc($r)) {
            array_push($this->classes, [$row['id'], $row['name']]);
        }
        if (count($this->classes) < 2) {
            throw new \Exception("captcha: add classes");
        }
        do {
            $cid = sha1(rand());
            $q = "select count(*) from captcha.answer where id='$cid'";
            $c = \db\getCount($q);
        } while ($c !== 0);
        $this->timeout = time() + self::TOUTSEC;
        $answer = $this->makeAnswer();
        $q = "insert into captcha.answer (id,value,timeout) values ('{$cid}','{$answer}',{$this->timeout})";
        \db\getDataM($q);
        $this->makeImages();
        if (!setcookie('cid', $cid)) {
            throw new \Exception('set cookie failed');
        }
        return [
            'message' => 'captcha',
            'param' => [
                'image' => $this->getImages(),
                'clas' => $this->getClasses(),
                'timeout' => $this->getTimeout()
            ]
        ];
    }

    private function makeAnswer() {
        $a = [];
        for ($i = 0; $i < count($this->images); $i++) {
            $a[] = $this->images[i][1];
        }
        return implode('_', $a);
    }

    private function makeImages() {
        global $basePath;
        foreach ($this->images as $v) {
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
                $this->images_data[] = base64_encode($data);
                imagedestroy($i);
                imagedestroy($ni);
            } else {
                throw new \Exception('image file not found');
            }
        }
    }

    private function getClasses() {
        return $this->classes;
    }

    private function getImages() {
        return $this->images_data;
    }

    private function getTimeout() {
        return $this->timeout;
    }

}

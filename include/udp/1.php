<?php

namespace udp;

$udp;

function init($addr, $port, $timeout) {
    global $udp;
    $udp_addr = "udp://" . $addr . ":" . $port;
    if (!$timeout) {
        $timeout = 3;
    }
    $udp = stream_socket_client($udp_addr, $errno, $errstr);
    if (!$udp) {
        throw new \Exception("udp connection failed: $errno - $errstr");
    }
    stream_set_timeout($udp, $timeout);
}

function init1() {
    global $uds_path, $uds;
    $errorno = -1;
    $errorstr = "";
    $timeout = 2;
    $uds = stream_socket_client('unix://' . $uds_path, $errorno, $errorstr, $timeout);
    if (!$uds) {
        throw new \Exception("uds connection failed: " . $errorstr . "; error number: " . $errorno);
    }
    stream_set_timeout($uds, $timeout);
    stream_set_blocking($uds, 1);
}

function suspend() {
    global $udp;
    fclose($udp);
}

function sendBuf($buf) {
    global $udp;
    return fwrite($udp, $buf);
}

function getBuf($buf_size) {
    global $udp;
    return fread($udp, $buf_size);
}

function getText($buf_size) {
    $text = "";
    $str = "";
    while (true) {
        $str = getBuf($buf_size);
        if (strpos($str, "\n\n") !== false) {
            break;
        }
        $text .= $str;
    }
    return $text;
}

//send command and do not wait for response
function sgCmdNR($qnf, $cmd) {
    global $udp;
    fwrite($udp, acp_getPackFromCmd($qnf, $cmd));
}

function getIrgData() {
    global $udp;
    $packet = acp_packFromCmd(ACP_QUANTIFIER_SPECIFIC, ACP_CMD_IRG_VALVE_GET_DATA);
    fwrite($udp, $packet);
    return fread($udp, BUF_SIZE);
}

function getDataS1(&$q, $s) {
    global $uds;
    $r = "";
    if (!fwrite($uds, $q)) {
        throw new \Exception("getDataS: can not write");
    }
    if (!($r = fread($uds, $s))) {
        throw new \Exception("getDataS: can not read response");
    }
    return $r;
}

function sgInt1($q) {
    global $uds;
    if (!fwrite($uds, $q)) {
        throw new \Exception("sgInt: can not write");
    }
    if (!($r = fread($uds, PHP_INT_SIZE))) {
        throw new \Exception("sgInt: can not read response");
    }
    $data = unpack("i", $r);
    return $data[1];
}

function sgStr1($q) {
    global $uds;
    if (!fwrite($uds, $q)) {
        throw new \Exception("sgStr: can not write");
    }
    $s = "";
    while (true) {
        $r = fread($uds, 1);
        if ($r === "") {
            break;
        }
        $s.=$r;
    }
    return $s;
}

function getIntSeq1($q) {
    \uds\init();
    $kind = \uds\getKind($q);
    if ($kind === "t") {
        $size = \uds\getInt();
        if ($size > 0) {
            $r = \uds\getData($size);
            $data = unpack("i*", $r);
            return $data;
        }
    } else {
        throw new \Exception("getIntSeq: controller refusal");
    }
    return NULL;
}

function sendQ1($q) {
    global $uds;
    if (!fwrite($uds, $q)) {
        throw new \Exception("sendQ: can not write");
    }
}

function getData1($s) {
    global $uds;
    if (!($r = fread($uds, $s))) {
        throw new \Exception("getData: can not read response");
    }
    return $r;
}

function getKind1($q) {
    global $uds;
    $r = "";
    if (!fwrite($uds, $q)) {
        throw new \Exception("getKind: can not write");
    }
    if (!($r = fread($uds, 1))) {
        throw new \Exception("getKind: can not read response");
    }
    $data = unpack("C", $r);
    return chr($data[1]);
}

function getInt1() {
    global $uds;
    if (!($r = fread($uds, PHP_INT_SIZE))) {
        throw new \Exception("getInt: can not read response");
    }
    $data = unpack("i", $r);
    return $data[1];
}

function getChar1() {
    global $uds;
    if (!($r = fread($uds, PHP_INT_SIZE))) {
        throw new \Exception("getChar: can not read response");
    }
    $data = unpack("C", $r);
    return $data[1];
}

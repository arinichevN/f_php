<?php

namespace acp;

define("ACP_BUF_SIZE", 508);
define("ACP_CMD_APP_START", '+');
define("ACP_CMD_APP_STOP", '-');
define("ACP_CMD_APP_EXIT", '*');
define("ACP_CMD_APP_RESET", '&');
define("ACP_CMD_APP_PING", '~');
define("ACP_CMD_APP_HELP", '?');
define("ACP_CMD_APP_PRINT", '@');
define("ACP_CMD_APP_TIME", 'T');
define("ACP_CMD_APP_NO", '_');

define("ACP_RESP_APP_BUSY", "B");
define("ACP_RESP_APP_IDLE", "I");

define("ACP_CMD_GET_FTS", 'a');
define("ACP_CMD_GET_INT", 'b');
define("ACP_CMD_LOG_START", 'c');
define("ACP_CMD_LOG_STOP", 'd');
define("ACP_CMD_SET_FLOAT", 'e');
define("ACP_CMD_SET_INT", 'f');

define("ACP_CMD_SET_DUTY_CYCLE_PWM", 'g');
define("ACP_CMD_SET_DUTY_CYCLE_PM", 'h');
define("ACP_CMD_SET_PWM_PERIOD", 'i');
define("ACP_CMD_SET_PM_DUTY_TIME_MIN", 'j');
define("ACP_CMD_SET_PM_IDLE_TIME_MIN", 'k');

define("ACP_CMD_STOP", 'l');
define("ACP_CMD_START", 'm');
define("ACP_CMD_RESET", 'n');

define("ACP_CMD_IRG_VALVE_TURN_ON", 'o');
define("ACP_CMD_IRG_VALVE_TURN_OFF", 'p');
define("ACP_CMD_IRG_VALVE_GET_DATA", 'q');
define("ACP_CMD_IRG_GET_TIME", 'r');
define("ACP_CMD_IRG_VALVE_GET_DATA1", 's');
define("ACP_CMD_IRG_PROG_MTURN", 't');

define("ACP_CMD_REGSMP_PROG_GET_DATA_RUNTIME", 'q');
define("ACP_CMD_REGSMP_PROG_GET_DATA_INIT", 'r');
define("ACP_CMD_REGSMP_PROG_SET_HEATER_POWER", 's');
define("ACP_CMD_REGSMP_PROG_SET_COOLER_POWER", 't');
define("ACP_CMD_REGSMP_PROG_SWITCH_STATE", 'v');

define("ACP_CMD_LGR_PROG_GET_DATA", 'q');

define("ACP_QUANTIFIER_BROADCAST", '!');
define("ACP_QUANTIFIER_SPECIFIC", '.');

define("ACP_RESP_REQUEST_FAILED", "F");
define("ACP_RESP_REQUEST_SUCCEEDED", "T");
define("ACP_RESP_REQUEST_SUCCEEDED_PARTIAL", "P");
define("ACP_RESP_RESULT_UNKNOWN", "R");
define("ACP_RESP_COMMAND_UNKNOWN", "U");
define("ACP_RESP_QUANTIFIER_UNKNOWN", "Q");
define("ACP_RESP_CRC_ERROR", "C");
define("ACP_RESP_BUF_OVERFLOW", "O");


$crc8_table = array(
    0x00, 0x3e, 0x7c, 0x42, 0xf8, 0xc6, 0x84, 0xba, 0x95, 0xab, 0xe9, 0xd7,
    0x6d, 0x53, 0x11, 0x2f, 0x4f, 0x71, 0x33, 0x0d, 0xb7, 0x89, 0xcb, 0xf5,
    0xda, 0xe4, 0xa6, 0x98, 0x22, 0x1c, 0x5e, 0x60, 0x9e, 0xa0, 0xe2, 0xdc,
    0x66, 0x58, 0x1a, 0x24, 0x0b, 0x35, 0x77, 0x49, 0xf3, 0xcd, 0x8f, 0xb1,
    0xd1, 0xef, 0xad, 0x93, 0x29, 0x17, 0x55, 0x6b, 0x44, 0x7a, 0x38, 0x06,
    0xbc, 0x82, 0xc0, 0xfe, 0x59, 0x67, 0x25, 0x1b, 0xa1, 0x9f, 0xdd, 0xe3,
    0xcc, 0xf2, 0xb0, 0x8e, 0x34, 0x0a, 0x48, 0x76, 0x16, 0x28, 0x6a, 0x54,
    0xee, 0xd0, 0x92, 0xac, 0x83, 0xbd, 0xff, 0xc1, 0x7b, 0x45, 0x07, 0x39,
    0xc7, 0xf9, 0xbb, 0x85, 0x3f, 0x01, 0x43, 0x7d, 0x52, 0x6c, 0x2e, 0x10,
    0xaa, 0x94, 0xd6, 0xe8, 0x88, 0xb6, 0xf4, 0xca, 0x70, 0x4e, 0x0c, 0x32,
    0x1d, 0x23, 0x61, 0x5f, 0xe5, 0xdb, 0x99, 0xa7, 0xb2, 0x8c, 0xce, 0xf0,
    0x4a, 0x74, 0x36, 0x08, 0x27, 0x19, 0x5b, 0x65, 0xdf, 0xe1, 0xa3, 0x9d,
    0xfd, 0xc3, 0x81, 0xbf, 0x05, 0x3b, 0x79, 0x47, 0x68, 0x56, 0x14, 0x2a,
    0x90, 0xae, 0xec, 0xd2, 0x2c, 0x12, 0x50, 0x6e, 0xd4, 0xea, 0xa8, 0x96,
    0xb9, 0x87, 0xc5, 0xfb, 0x41, 0x7f, 0x3d, 0x03, 0x63, 0x5d, 0x1f, 0x21,
    0x9b, 0xa5, 0xe7, 0xd9, 0xf6, 0xc8, 0x8a, 0xb4, 0x0e, 0x30, 0x72, 0x4c,
    0xeb, 0xd5, 0x97, 0xa9, 0x13, 0x2d, 0x6f, 0x51, 0x7e, 0x40, 0x02, 0x3c,
    0x86, 0xb8, 0xfa, 0xc4, 0xa4, 0x9a, 0xd8, 0xe6, 0x5c, 0x62, 0x20, 0x1e,
    0x31, 0x0f, 0x4d, 0x73, 0xc9, 0xf7, 0xb5, 0x8b, 0x75, 0x4b, 0x09, 0x37,
    0x8d, 0xb3, 0xf1, 0xcf, 0xe0, 0xde, 0x9c, 0xa2, 0x18, 0x26, 0x64, 0x5a,
    0x3a, 0x04, 0x46, 0x78, 0xc2, 0xfc, 0xbe, 0x80, 0xaf, 0x91, 0xd3, 0xed,
    0x57, 0x69, 0x2b, 0x15);

function crc_update($crc, $b) {

    for ($i = 8; $i; $i--) {
        $crc = (($crc ^ $b) & 1) ? ($crc >> 1) ^ 0b10001100 : ($crc >> 1);
        $b >>= 1;
    }
    return $crc;
}

function crc_update_by_str($crc, $str) {
    $len = strlen($str);

    $i = 0;
    while ($len--) {
        $extract = ord($str[$i]);
        for ($t = 8; $t; $t--) {
            $sum = ($crc ^ $extract) & 0x01;
            $crc >>= 1;
            if ($sum) {
                $crc ^= 0x8C;
            }
            $extract >>= 1;
        }
        $i++;
    }

    return $crc;
}

function crc_check($buf_str) {
    $str = "";
    for ($i = 0; $i < strlen($buf_str) - 1; $i++) {
        $str .= $buf_str[$i];
    }
    $crc_fact = 0x00;
    $crc_fact = crc_update_by_str($crc_fact, $str);
    if ($crc_fact !== ord($buf_str[strlen($buf_str) - 1])) {
        return 0;
    }
    return 1;
}

function getPackFromCmd($qnf, $cmd) {
    $packet = $qnf . $cmd . "\n\n";
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $packet);
    return $packet;
}

function sendPackI1($cmd, $i1_list) {
    $buf = ACP_QUANTIFIER_SPECIFIC . $cmd . "\n";
    foreach ($i1_list as $value) {
        $buf.=$value . "\n";
    }
    $buf.="\n";
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $buf);
    $buf.=chr($crc);
    $buf_len = strlen($buf);
    $n = \udp\sendBuf($buf);
    if ($n !== $buf_len) {
        throw new \Exception("sendPackI1: sendBuf: expected to write: $buf_len, but written: $n");
    }
}

function sendPackI1F1($cmd, $list) {
    $buf = ACP_QUANTIFIER_SPECIFIC . $cmd . "\n";
    foreach ($list as $value) {
        $buf.=$value['p0'] . "_" . $value['p1'] . "\n";
    }
    $buf.="\n";
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $buf);
    $buf.=chr($crc);
    $buf_len = strlen($buf);
    $n = \udp\sendBuf($buf);
    if ($n !== $buf_len) {
        throw new \Exception("sendPackI1F1: sendBuf: expected to write: $buf_len, but written: $n");
    }
}

function sendPackBroadcast($cmd) {
    $buf = ACP_QUANTIFIER_BROADCAST . $cmd . "\n\n";
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $buf);
    $buf.=chr($crc);
    \udp\sendBuf($buf);
}

function getBufParseIrgData() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
}

function getBufParseStateData() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getBufParseStateData: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getBufParseStateData: crc check failed");
    }
    return $buf[1];
}

function parseIrgValveState($buf_str) {
    $data = [];
    $str = "";
    $last_char = NULL;
    for ($i = 0; i < strlen($buf_str); $i++) {
        if ($i < 3) {
            $last_char = $buf_str[$i];
            continue;
        }
        if ($buf_str[$i] === "\n") {
            if ($last_char === "\n") {
                return $data;
            }
            $arr = explode('_', $str, 12);
            if (count($arr) !== 12) {
                throw new \Exception("parseIrgValveState: bad format");
            }
            \array_push($data, [
                'id' => $arr[0],
                'state' => $arr[1],
                'state_wp' => $arr[2],
                'state_rn' => $arr[3],
                'state_tc' => $arr[4],
                'step_tc' => $arr[5],
                'crepeat' => $arr[6],
                'blocked_rn' => $arr[7],
                'cbusy_time' => $arr[8],
                'time_passed_main' => $arr[9],
                'time_passed_tc' => $arr[10],
                'last_output' => $arr[11]
            ]);
            $str = null;
        }
        $str.=$buf_str[$i];
        $last_char = $buf_str[$i];
    }
    return $data;
}

function getIrgValveState() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getIrgValveState: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getIrgValveState: crc check failed");
    }
    $data = parseIrgValveState($buf);
    if ($data === false) {
        throw new \Exception("getIrgValveState: bad format");
    }
    return $data;
}

function parseIrgValveState1($buf_str) {
    $data = [];
    $str = "";
    $last_char = NULL;
    for ($i = 0; i < strlen($buf_str); $i++) {
        if ($i < 3) {
            $last_char = $buf_str[$i];
            continue;
        }
        if ($buf_str[$i] === "\n") {
            if ($last_char === "\n") {
                return $data;
            }
            $arr = explode('_', $str, 17);
            if (count($arr) !== 17) {
                throw new \Exception("parseIrgValveState1: bad format");
            }
            \array_push($data, [
                'id' => $arr[0],
                'output' => $arr[1],
                'rain' => $arr[2],
                'is_master' => $arr[3],
                'master_count' => $arr[4],
                'running_prog_id' => $arr[5],
                'prog_loaded' => $arr[6],
                'state_main' => $arr[7],
                'state_wp' => $arr[8],
                'state_rn' => $arr[9],
                'state_tc' => $arr[10],
                'crepeat' => $arr[11],
                'blocked_rn' => $arr[12],
                'time_passed' => $arr[13],
                'time_specified' => $arr[14],
                'time_rest_tc' => $arr[15],
                'em_peer_active' => $arr[16]
            ]);
            $str = null;
        }
        $str.=$buf_str[$i];
        $last_char = $buf_str[$i];
    }
    return $data;
}

function getIrgValveState1() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getIrgValveState1: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getIrgValveState1: crc check failed");
    }
    $data = parseIrgValveState1($buf);
    if ($data === false) {
        throw new \Exception("getIrgValveState1: bad format");
    }
    return $data;
}

function parseLgrState($buf_str) {
    $data = [];
    $str = "";
    $last_char = NULL;
    for ($i = 0; i < strlen($buf_str); $i++) {
        if ($i < 3) {
            $last_char = $buf_str[$i];
            continue;
        }
        if ($buf_str[$i] === "\n") {
            if ($last_char === "\n") {
                return $data;
            }
            $arr = explode('_', $str, 3);
            if (count($arr) !== 3) {
                throw new \Exception("parseLgrState: bad format");
            }
            \array_push($data, [
                'id' => $arr[0],
                'interval_min' => $arr[1],
                'max_rows' => $arr[2]
            ]);
            $str = null;
        }
        $str.=$buf_str[$i];
        $last_char = $buf_str[$i];
    }
    return $data;
}

function getLgrState() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getLgrState: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getLgrState: crc check failed");
    }
    $data = parseLgrState($buf);
    if ($data === false) {
        throw new \Exception("getLgrState: bad format");
    }
    return $data;
}

function parseRegsmpDataRuntime($buf_str) {
    $data = [];
    $str = "";
    $last_char = NULL;
    $field_count = 8;
    for ($i = 0; i < strlen($buf_str); $i++) {
        if ($i < 3) {
            $last_char = $buf_str[$i];
            continue;
        }
        if ($buf_str[$i] === "\n") {
            if ($last_char === "\n") {
                return $data;
            }
            $arr = explode('_', $str, $field_count);
            if (count($arr) !== $field_count) {
                throw new \Exception("parseRegsmpDataRuntime: bad format");
            }
            \array_push($data, [
                'id' => $arr[0],
                'state' => $arr[1],
                'state_r' => $arr[2],
                'output_heater' => $arr[3],
                'output_cooler' => $arr[4],
                'change_tm_rest' => $arr[5],
                'sensor_value' => $arr[6],
                'sensor_state' => $arr[7]
            ]);
            $str = null;
        }
        $str.=$buf_str[$i];
        $last_char = $buf_str[$i];
    }
    return $data;
}

function getRegsmpDataRuntime() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getRegsmpDataRuntime: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getRegsmpDataRuntime: crc check failed");
    }
    $data = parseRegsmpDataRuntime($buf);
    if ($data === false) {
        throw new \Exception("getRegsmpDataRuntime: bad format");
    }
    return $data;
}

function parseRegsmpDataInit($buf_str) {
    $data = [];
    $str = "";
    $last_char = NULL;
    $field_count = 11;
    for ($i = 0; i < strlen($buf_str); $i++) {
        if ($i < 3) {
            $last_char = $buf_str[$i];
            continue;
        }
        if ($buf_str[$i] === "\n") {
            if ($last_char === "\n") {
                return $data;
            }
            $arr = explode('_', $str, $field_count);
            if (count($arr) !== $field_count) {
                throw new \Exception("parseRegsmpDataInit: bad format");
            }
            \array_push($data, [
                'id' => $arr[0],
                'goal' => $arr[1],
                'mode' => $arr[2],
                'delta' => $arr[3],
                'pid_h_kp' => $arr[4],
                'pid_h_ki' => $arr[5],
                'pid_h_kd' => $arr[6],
                'pid_c_kp' => $arr[7],
                'pid_c_ki' => $arr[8],
                'pid_c_kd' => $arr[9],
                'change_gap' => $arr[10]
            ]);
            $str = null;
        }
        $str.=$buf_str[$i];
        $last_char = $buf_str[$i];
    }
    return $data;
}

function getRegsmpDataInit() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getRegsmpDataInit: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getRegsmpDataInit: crc check failed");
    }
    $data = parseRegsmpDataInit($buf);
    if ($data === false) {
        throw new \Exception("getRegsmpDataInit: bad format");
    }
    return $data;
}

function parseDate($buf_str) {
    $str = "";
    for ($i = 0; i < strlen($buf_str); $i++) {
        if ($i < 3) {
            continue;
        }
        if ($buf_str[$i] === "\n") {
            break;
        }
        $str.=$buf_str[$i];
    }
    $arr = explode('_', $str, 6);
    if (count($arr) !== 6) {
        throw new \Exception("parseDate: bad format");
    }
    $data = [
        'year' => $arr[0],
        'month' => $arr[1],
        'day' => $arr[2],
        'hour' => $arr[3],
        'min' => $arr[4],
        'sec' => $arr[5]
    ];
    return $data;
}

function getDate() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getDate: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getDate: crc check failed");
    }
    $data = parseDate($buf);
    if ($data === false) {
        throw new \Exception("getDate: bad format");
    }
    return $data;
}

function parseFTS($buf_str) {
    $data = [];
    $str = "";
    $last_char = NULL;
    for ($i = 0; i < strlen($buf_str); $i++) {
        if ($i < 3) {
            $last_char = $buf_str[$i];
            continue;
        }
        if ($buf_str[$i] === "\n") {
            if ($last_char === "\n") {
                return $data;
            }
            $arr = explode('_', $str, 5);
            if (count($arr) !== 5) {
                throw new \Exception("parseIrgValveState1: bad format");
            }
            \array_push($data, [
                'id' => $arr[0],
                'value' => $arr[1],
                'tv_sec' => $arr[2],
                'tv_nsec' => $arr[3],
                'state' => $arr[4]
            ]);
            $str = null;
        }
        $str.=$buf_str[$i];
        $last_char = $buf_str[$i];
    }
    return $data;
}

function getFTS() {
    $buf = \udp\getBuf(ACP_BUF_SIZE);
    if (strlen($buf) === 0) {
        throw new \Exception("getFTS: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getFTS: crc check failed");
    }
    $data = parseFTS($buf);
    if ($data === false) {
        throw new \Exception("getFTS: bad format");
    }
    return $data;
}

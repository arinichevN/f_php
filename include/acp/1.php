<?php

namespace acp;

define("ACP_DELIMITER_COLUMN", "_");
define("ACP_DELIMITER_ROW", "\n");
define("ACP_DELIMITER_CMD", "\n");
define("ACP_DELIMITER_CRC", "\n");

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

define("ACP_CMD_REGONF_PROG_GET_DATA_RUNTIME", 'q');
define("ACP_CMD_REGONF_PROG_GET_DATA_INIT", 'r');
define("ACP_CMD_REGONF_PROG_SET_HEATER_POWER", 's');
define("ACP_CMD_REGONF_PROG_SET_COOLER_POWER", 't');
define("ACP_CMD_REGONF_PROG_SWITCH_STATE", 'v');
define("ACP_CMD_REGONF_PROG_SET_GOAL", 'u');
define("ACP_CMD_REGONF_PROG_SET_DELTA", 'w');
define("ACP_CMD_REGONF_PROG_ENABLE", 'x');
define("ACP_CMD_REGONF_PROG_DISABLE", 'y');


define("ACP_CMD_ALR_PROG_GET_DATA_RUNTIME", 'q');
define("ACP_CMD_ALR_PROG_GET_DATA_INIT", 'r');
define("ACP_CMD_ALR_PROG_SET_GOAL", 'u');
define("ACP_CMD_ALR_PROG_SET_DELTA", 'w');
define("ACP_CMD_ALR_PROG_SET_SMS", 's');
define("ACP_CMD_ALR_PROG_SET_RING", 't');
define("ACP_CMD_ALR_PROG_ENABLE", 'x');
define("ACP_CMD_ALR_PROG_DISABLE", 'y');
define("ACP_CMD_ALR_ALARM_DISABLE", 'z');
define("ACP_CMD_ALR_ALARM_GET", 'A');

define("ACP_CMD_LGR_PROG_GET_DATA_INIT", 'q');
define("ACP_CMD_LGR_PROG_GET_DATA_RUNTIME", 'r');
define("ACP_CMD_LGR_PROG_ENABLE", 'x');
define("ACP_CMD_LGR_PROG_DISABLE", 'y');

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


$crc8_table = [
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
    0x57, 0x69, 0x2b, 0x15];

function crc_update($crc, $b) {
    for ($i = 8; $i; $i--) {
        $crc = (($crc ^ $b) & 1) ? ($crc >> 1) ^ 0b10001100 : ($crc >> 1);
        $b >>= 1;
    }
    return $crc;
}

function crc_update_by_str($crc, $str) {
    $len = \strlen($str);
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
    for ($i = 0; $i < \strlen($buf_str) - 1; $i++) {
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
    $packet = $qnf . $cmd . ACP_DELIMITER_CMD . ACP_DELIMITER_CRC;
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $packet);
    return $packet;
}

function sendPackI1($cmd, $i1_list) {
    $buf = ACP_QUANTIFIER_SPECIFIC . $cmd . ACP_DELIMITER_CMD;
    foreach ($i1_list as $value) {
        $buf.=$value . ACP_DELIMITER_ROW;
    }
    $buf.=ACP_DELIMITER_CRC;
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $buf);
    $buf.=chr($crc);
    $buf_len = \strlen($buf);
    $n = \sock\sendBuf($buf);
    if ($n !== $buf_len) {
        throw new \Exception("sendPackI1: sendBuf: expected to write: $buf_len, but written: $n");
    }
}

function sendPackI1F1($cmd, $list) {
    $buf = ACP_QUANTIFIER_SPECIFIC . $cmd . ACP_DELIMITER_CMD;
    foreach ($list as $value) {
        $v0 = intval($value['p0']);
        $v1 = floatval($value['p1']);
        $buf.=$v0 . ACP_DELIMITER_COLUMN . $v1 . ACP_DELIMITER_ROW;
    }
    $buf.=ACP_DELIMITER_CRC;
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $buf);
    $buf.=chr($crc);
    $buf_len = \strlen($buf);
    $n = \sock\sendBuf($buf);
    if ($n !== $buf_len) {
        throw new \Exception("sendPackI1F1: sendBuf: expected to write: $buf_len, but written: $n");
    }
}

function sendPackI2($cmd, $list) {
    $buf = ACP_QUANTIFIER_SPECIFIC . $cmd . ACP_DELIMITER_CMD;
    foreach ($list as $value) {
        $v0 = intval($value['p0']);
        $v1 = intval($value['p1']);
        $buf.=$v0 . ACP_DELIMITER_COLUMN . $v1 . ACP_DELIMITER_ROW;
    }
    $buf.=ACP_DELIMITER_CRC;
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $buf);
    $buf.=chr($crc);
    $buf_len = \strlen($buf);
    $n = \sock\sendBuf($buf);
    if ($n !== $buf_len) {
        throw new \Exception("sendPackI2: sendBuf: expected to write: $buf_len, but written: $n");
    }
}

function sendPackBroadcast($cmd) {
    $buf = ACP_QUANTIFIER_BROADCAST . $cmd . ACP_DELIMITER_CMD . ACP_DELIMITER_CRC;
    $crc = 0x00;
    $crc = crc_update_by_str($crc, $buf);
    $buf.=chr($crc);
    \sock\sendBuf($buf);
}

function getBufParseStateData() {
    $buf = \sock\getBuf(ACP_BUF_SIZE);
    if (\strlen($buf) === 0) {
        throw new \Exception("getBufParseStateData: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getBufParseStateData: crc check failed");
    }
    return $buf[1];
}

function rowToArr($str, $items_count) {
    $data = \explode(ACP_DELIMITER_COLUMN, $str, $items_count);
    if (\count($data) !== $items_count) {
        throw new \Exception("rowToArr: bad format");
    }
    return $data;
}

function getData($buf_str, $rowArr) {
    $data = [];
    $str = "";
    $last_char = NULL;
    $field_count = \count($rowArr);
    for ($i = 0; $i < \strlen($buf_str); $i++) {
        if ($i < 3) {
            $last_char = $buf_str[$i];
            continue;
        }
        if ($buf_str[$i] === ACP_DELIMITER_CRC && $last_char === ACP_DELIMITER_ROW) {
            return $data;
        }
        if ($buf_str[$i] === ACP_DELIMITER_ROW) {
            $arr = rowToArr($str, $field_count);
            $row = array_merge([], $rowArr);
            $j = 0;
            foreach ($row as $key => $value) {
                $row[$key] = $arr[$j];
                $j++;
            }
            \array_push($data, $row);
            $str = null;
        }
        $str.=$buf_str[$i];
        $last_char = $buf_str[$i];
    }
    return $data;
}

function parseResponse($rowArr) {
    $buf = \sock\getBuf(ACP_BUF_SIZE);
    if (\strlen($buf) === 0) {
        throw new \Exception("parseResponse: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("parseResponse: crc check failed");
    }
    $data = getData($buf, $rowArr);
    if ($data === false) {
        throw new \Exception("parseResponse: bad format");
    }
    return $data;
}

function getIrgValveState() {
    return parseResponse([
        'id' => null,
        'state' => null,
        'state_wp' => null,
        'state_rn' => null,
        'state_tc' => null,
        'step_tc' => null,
        'crepeat' => null,
        'blocked_rn' => null,
        'cbusy_time' => null,
        'time_passed_main' => null,
        'time_passed_tc' => null,
        'last_output' => null
    ]);
}

function getIrgValveState1() {
    return parseResponse([
        'id' => null,
        'output' => null,
        'rain' => null,
        'is_master' => null,
        'master_count' => null,
        'running_prog_id' => null,
        'prog_loaded' => null,
        'state_main' => null,
        'state_wp' => null,
        'state_rn' => null,
        'state_tc' => null,
        'crepeat' => null,
        'blocked_rn' => null,
        'time_passed' => null,
        'time_specified' => null,
        'time_rest_tc' => null,
        'em_peer_active' => null
    ]);
}

function getLgrDataInit() {
    return parseResponse([
        'id' => null,
        'interval_min' => null,
        'max_rows' => null
    ]);
}

function getLgrDataRuntime() {
    return parseResponse([
        'id' => null,
        'state' => null,
        'log_time_rest' => null
    ]);
}

function getRegsmpDataRuntime() {
    return parseResponse([
        'id' => null,
        'state' => null,
        'state_r' => null,
        'output_heater' => null,
        'output_cooler' => null,
        'change_tm_rest' => null,
        'sensor_value' => null,
        'sensor_state' => null
    ]);
}

function getRegsmpDataInit() {
    return parseResponse([
        'id' => null,
        'goal' => null,
        'mode' => null,
        'delta' => null,
        'pid_h_kp' => null,
        'pid_h_ki' => null,
        'pid_h_kd' => null,
        'pid_c_kp' => null,
        'pid_c_ki' => null,
        'pid_c_kd' => null,
        'change_gap' => null
    ]);
}

function getRegonfDataRuntime() {
    return parseResponse([
        'id' => null,
        'state' => null,
        'state_r' => null,
        'output_heater' => null,
        'output_cooler' => null,
        'change_tm_rest' => null,
        'sensor_value' => null,
        'sensor_state' => null
    ]);
}

function getRegonfDataInit() {
    return parseResponse([
        'id' => null,
        'goal' => null,
        'delta' => null,
        'change_gap' => null
    ]);
}

function getAlrDataInit() {
    return parseResponse([
        'id' => null,
        'description' => null,
        'good_value' => null,
        'good_delta' => null,
        'check_interval' => null,
        'cope_duration' => null,
        'phone_number_group_id' => null,
        'sms' => null,
        'ring' => null
    ]);
}

function getAlrDataRuntime() {
    return parseResponse([
        'id' => null,
        'state' => null,
        'cope_time_rest' => null
    ]);
}

function parseDate($buf_str) {
    $str = "";
    for ($i = 0; $i < \strlen($buf_str); $i++) {
        if ($i < 3) {
            continue;
        }
        if ($buf_str[$i] === "\n") {
            break;
        }
        $str.=$buf_str[$i];
    }
    $arr = rowToArr($str, 6);
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
    $buf = \sock\getBuf(ACP_BUF_SIZE);
    if (\strlen($buf) === 0) {
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

function getFTS() {
    return parseResponse([
        'id' => null,
        'value' => null,
        'tv_sec' => null,
        'tv_nsec' => null,
        'state' => null
    ]);
}

function parseString($buf_str) {
    $str = "";
    for ($i = 0; $i < \strlen($buf_str); $i++) {
        if ($i < 3) {
            continue;
        }
        if ($buf_str[$i] === "\n") {
            break;
        }
        $str.=$buf_str[$i];
    }
    return $str;
}

function getString() {
    $buf = \sock\getBuf(ACP_BUF_SIZE);
    if (\strlen($buf) === 0) {
        throw new \Exception("getString: controller returned nothing");
    }
    if (!crc_check($buf)) {
        throw new \Exception("getString: crc check failed");
    }
    $data = parseString($buf);
    if ($data === false) {
        throw new \Exception("getString: bad format");
    }
    return $data;
}

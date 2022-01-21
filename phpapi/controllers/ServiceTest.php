<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	echo 'nope';
	die();
}

require_once(JSON);

$result_code = 0;
$data['server_time'] = time();
$data['result_code'] = $result_code;

send_json($data);
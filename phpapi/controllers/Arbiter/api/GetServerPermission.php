<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(SQL);
require_once(JSON);

$result_code = 0;
$permission = 0;

$data['permission'] = $permission;
$data['result_code'] = $result_code;

send_json($data);
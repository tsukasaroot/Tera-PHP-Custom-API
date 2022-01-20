<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$result_code = 0;
$permission = 0;

$data['permission'] = $permission;
$data['result_code'] = $result_code;

header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
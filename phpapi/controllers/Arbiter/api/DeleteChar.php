<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(JSON);

$msg = '';
$result_code = 0;
$data = [];

$_POST = get_json_input(file_get_contents('php://input'));

file_put_contents('logs.txt', print_r($_POST, true));

if ($result_code > 0)
	$data['msg'] = $msg;

$data['result_code'] = $result_code;

send_json($data);
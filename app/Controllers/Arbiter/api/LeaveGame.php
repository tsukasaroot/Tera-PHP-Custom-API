<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(SQL);
require_once(JSON);

$_POST = get_json_input(file_get_contents('php://input'));
$msg = '';
$result_code = 0;

if (!isset($_POST['user_srl']) || !isset($_POST['serviceCode'])) {
	$result_code = 2;
	$msg = 'Error with LeaveGame';
}

$data['result_code'] = $result_code;
if ($result_code > 0)
	$data['msg'] = $msg;

send_json($data);
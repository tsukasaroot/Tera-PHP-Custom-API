<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$res = print_r($_POST, true);
file_put_contents('logs.txt', $res, FILE_APPEND);

$returnCode = 0;

$data['result_code'] = $returnCode;

header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);
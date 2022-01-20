<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	echo 'nope';
	die();
}

$result_code = 0;
$date = new DateTime();
$date = $date->getTimestamp();
$data['server_time'] = $date;
$data['result_code'] = $result_code;

header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

file_put_contents('logs.txt', "ServiceTest\n", FILE_APPEND);
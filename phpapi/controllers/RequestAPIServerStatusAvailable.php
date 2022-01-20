<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	echo 'nope';
	die();
}

$data['Return'] = true;
header('Content-Length: ' . strlen(json_encode($data)));
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

file_put_contents('logs.txt', "RequestAPIServerStatusAvailable\n", FILE_APPEND);
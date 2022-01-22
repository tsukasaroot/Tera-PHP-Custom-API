<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(SQL);
require_once(JSON);

$_POST = get_json_input(file_get_contents('php://input'));

$returnCode = 0;
$data = [];
$msg = '';

fcgiAddBenefitResponse();

if ($returnCode > 0)
	$data['msg'] = $msg;

send_json($data);

function fcgiAddBenefitResponse()
{
	$RESTGetUrl = "/add_benefit/2800/6/1/86400";
	$ch = curl_init('http://127.0.0.1:10002/fcgi' . $RESTGetUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	$fcgiAddBenefitResponse = curl_exec($ch);
	curl_close($ch);
	file_put_contents('logs.txt', print_r($fcgiAddBenefitResponse, true), FILE_APPEND);
}
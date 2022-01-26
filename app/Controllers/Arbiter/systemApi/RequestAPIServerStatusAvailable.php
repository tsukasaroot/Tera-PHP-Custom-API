<?php

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
	echo 'nope';
	die();
}

require_once(JSON);

$data['Return'] = true;

send_json($data);
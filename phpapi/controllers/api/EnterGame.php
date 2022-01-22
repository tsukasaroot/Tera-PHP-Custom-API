<?php

// Load character in Server or Game, to clarify

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(JSON);

$returnCode = 0;
$data['result_code'] = $returnCode;

send_json($data);
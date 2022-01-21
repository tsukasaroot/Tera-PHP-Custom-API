<?php

function get_json_input(string $json): int|array
{
	if ($json) {
		$var = [];
		$json = json_decode($json);
		
		foreach ($json as $k => $v) {
			$var[$k] = $v;
		}
		return $var;
	}
	return '';
}

function send_json(array $data): int
{
	
	if ($data) {
		header('Content-Length: ' . strlen(json_encode($data)));
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		return 0;
	}
	return -1;
}
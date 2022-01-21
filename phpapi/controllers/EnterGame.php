<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

$res = print_r($_POST, true);

file_put_contents('logs.txt', $res, FILE_APPEND);

$returnCode = 0;
<?php

$env = parse_ini_file('.env');
$conn = new mysqli($env['host'], $env['user'], $env['pwd'], $env['db']);
if ($conn->connect_error) {
	$conn->close();
	die("Connection failed: ");
}
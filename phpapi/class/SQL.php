<?php

$env = parse_ini_file('.env');
$conn = new mysqli($this->env['host'], $this->env['user'], $this->env['pwd'], $this->env['db']);
if ($conn->connect_error) {
	$conn->close();
	die("Connection failed: ");
}
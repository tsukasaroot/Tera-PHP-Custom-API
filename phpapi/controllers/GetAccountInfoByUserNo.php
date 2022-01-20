<?php

require_once(SQL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
	echo 'nope';
	die();
}

if ($_POST['r'] !== '478c98a0b14387f3966ebeec6b570348fffac684b96f1d2e48d0caa51b4b4adb') {
	$returnCode = 2;
}
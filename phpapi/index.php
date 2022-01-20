<?php

if (!DEFINED('SQL'))
	define('SQL', 'class/SQL.php');

$request = $_SERVER['REQUEST_URI'];
$request = substr($request, 1);

switch ($request) {
	case 'LauncherLoginAction':
		require 'controllers/LauncherLoginAction.php';
		break;
	case 'GetAccountInfoByUserNo':
		require 'controllers/GetAccountInfoByUserNo.php';
		break;
	case 'api/ServiceTest':
		require 'controllers/ServiceTest.php';
		break;
	case 'systemApi/RequestAPIServerStatusAvailable':
		require 'controllers/RequestAPIServerStatusAvailable.php';
		break;
	default:
		http_response_code(404);
		echo 'Error';
		break;
}
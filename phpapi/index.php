<?php

if (!DEFINED('SQL'))
	define('SQL', 'class/SQL.php');

if (!DEFINED('JSON'))
	define('JSON', 'class/JSON.php');

$request = $_SERVER['REQUEST_URI'];
$request = substr($request, 1);

switch ($request) {
	// Used by Client
	case 'api/GetUserInfo': //optimized fetch
		require 'controllers/GetUserInfo.php';
		break;
	case 'authApi/GameAuthenticationLogin': //optimized fetch
		require 'controllers/GameLoginJSON.php';
		break;
	case 'tera/LauncherLoginAction': //optimized fetch
		require 'controllers/LauncherLoginAction.php';
		break;
	case 'tera/GetAccountInfoByUserNo':
		require 'controllers/GetAccountInfoByUserNo.php';
		break;
	case 'api/EnterGame':
		require 'controllers/EnterGame.php';
		break;
	case 'api/LeaveGame':
		require 'controllers/LeaveGame.php';
		break;
	//Used by ArbiterGw
	case 'api/ServiceTest':
		require 'controllers/ServiceTest.php';
		break;
	case 'systemApi/RequestAPIServerStatusAvailable':
		require 'controllers/RequestAPIServerStatusAvailable.php';
		break;
	case 'api/GetServerPermission':
		require 'controllers/GetServerPermission.php';
		break;
	case 'api/ServerDown':
		require 'controllers/ServerDown.php';
		break;
	default:
		http_response_code(404);
		echo 'Error';
		break;
}
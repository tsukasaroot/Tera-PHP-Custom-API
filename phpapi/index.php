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
		require 'controllers/api/GetUserInfo.php';
		break;
	case 'authApi/GameAuthenticationLogin': //optimized fetch
		require 'controllers/authApi/GameLoginJSON.php';
		break;
	case 'tera/LauncherLoginAction': //optimized fetch
		require 'controllers/tera/LauncherLoginAction.php';
		break;
	case 'tera/GetAccountInfoByUserNo':
		require 'controllers/tera/GetAccountInfoByUserNo.php';
		break;
	case 'api/EnterGame':
		require 'controllers/api/EnterGame.php';
		break;
	case 'api/LeaveGame':
		require 'controllers/Arbiter/api/LeaveGame.php';
		break;
	//Used by ArbiterGw
	case 'api/ServiceTest':
		require 'controllers/Arbiter/api/ServiceTest.php';
		break;
	case 'systemApi/RequestAPIServerStatusAvailable':
		require 'controllers/Arbiter/systemApi/RequestAPIServerStatusAvailable.php';
		break;
	case 'api/GetServerPermission':
		require 'controllers/Arbiter/api/GetServerPermission.php';
		break;
	case 'api/ServerDown':
		require 'controllers/Arbiter/api/ServerDown.php';
		break;
	case 'api/CreateChar':
		require 'controllers/Arbiter/api/CreateChar.php';
		break;
	case 'api/DeleteChar':
		require 'controllers/Arbiter/api/DeleteChar.php';
		break;
	case 'api/ModifyChar':
		require 'controllers/Arbiter/api/ModifyChar.php';
		break;
	case 'api/UseChronoScroll':
		require '';
		break;
	default:
		http_response_code(404);
		echo 'Error';
		break;
}
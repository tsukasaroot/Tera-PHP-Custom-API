<?php

namespace Core;

class Routes
{
	public static function post(string $route, string $action)
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo 'nope';
			die();
		}
		
		$action = explode('@', $action);
		$action[0] =  'App\\Controllers\\' . $action[0];

		$obj = new $action[0]();
		call_user_func([ $obj, $action[1] ]);
	}
	
	public function create(string $request)
	{
		require('../routes/web.php');
		/*switch ($request) {
			// Used by Client
			case 'api/GetUserInfo': //optimized fetch
				require 'Controllers/api/GetUserInfo.php';
				break;
			case 'authApi/GameAuthenticationLogin': //optimized fetch
				require 'Controllers/authApi/GameLoginJSON.php';
				break;
			case 'tera/LauncherLoginAction': //optimized fetch
				require 'Controllers/tera/LauncherLoginAction.php';
				break;
			case 'tera/GetAccountInfoByUserNo':
				require 'Controllers/tera/GetAccountInfoByUserNo.php';
				break;
			case 'api/EnterGame':
				require 'Controllers/api/EnterGame.php';
				break;
			case 'api/LeaveGame':
				require 'Controllers/Arbiter/api/LeaveGame.php';
				break;
			//Used by ArbiterGw
			case 'api/ServiceTest':
				require 'Controllers/Arbiter/api/ServiceTest.php';
				break;
			case 'systemApi/RequestAPIServerStatusAvailable':
				require 'Controllers/Arbiter/systemApi/RequestAPIServerStatusAvailable.php';
				break;
			case 'api/GetServerPermission':
				require 'Controllers/Arbiter/api/GetServerPermission.php';
				break;
			case 'api/ServerDown':
				require 'Controllers/Arbiter/api/ServerDown.php';
				break;
			case 'api/CreateChar':
				require 'Controllers/Arbiter/api/CreateChar.php';
				break;
			case 'api/DeleteChar':
				require 'Controllers/Arbiter/api/DeleteChar.php';
				break;
			case 'api/ModifyChar':
				require 'Controllers/Arbiter/api/ModifyChar.php';
				break;
			case 'api/UseChronoScroll':
				require 'Controllers/Arbiter/api/UseChronoScroll.php';
				break;
			default:
				http_response_code(404);
				echo 'Error';
				break;
		}*/
	}
}
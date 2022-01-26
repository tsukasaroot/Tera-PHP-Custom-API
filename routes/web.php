<?php

use Core\Routes;

/**
 * Client
 */
Routes::post('tera/LauncherLoginAction', 'Tera\\teraController@login');
Routes::post('tera/GetAccountInfoByUserNo', 'Tera\\teraController@getAccountInfoByUserNo');
Routes::post('authApi/GameAuthenticationLogin','authApi\\GameLoginController@login');
Routes::post('api/GetUserInfo', 'Api\\getUserInfoController@info');
Routes::post('api/EnterGame', '');
Routes::post('api/LeaveGame', '');
/**
 * Arb_GW
 */
Routes::get('api/ServiceTest', '');
Routes::get('systemApi/RequestAPIServerStatusAvailable', '');
Routes::post('api/GetServerPermission', '');
Routes::post('api/ServerDown', '');
Routes::post('api/CreateChar', '');
Routes::post('api/CreateChar', '');
Routes::post('api/DeleteChar', '');
Routes::post('api/ModifyChar', '');
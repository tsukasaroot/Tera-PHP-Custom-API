<?php

use Core\Routes;

/**
 * Client
 */
Routes::post('/login', 'Tera\\teraController@login');
Routes::get('/getServerInfo', 'Tera\\teraController@getAccountInfoByUserNo');
Routes::post('/authApi/GameAuthenticationLogin','authApi\\GameLoginController@login');
Routes::post('/api/GetUserInfo', 'Api\\getUserInfoController@info');
Routes::post('/api/EnterGame', 'Api\\UserServerInteraction@EnterGame');
Routes::post('/api/LeaveGame', 'Api\\UserServerInteraction@LeaveGame');
Routes::post('/tera/register', 'Tera\\teraController@register');
/**
 * Arb_GW
 */
Routes::get('/api/ServiceTest', 'Arbiter\\ArbiterApi@ServiceTest');
Routes::get('/systemApi/RequestAPIServerStatusAvailable', 'Arbiter\\SystemApi@status');
Routes::post('/api/GetServerPermission', 'Arbiter\\ArbiterApi@GetServerPermission');
Routes::post('/api/ServerDown', 'Arbiter\\ArbiterApi@ServerDown');
Routes::post('/api/CreateChar', 'Arbiter\\ArbiterApi@CreateChar');
Routes::post('/api/DeleteChar', 'Arbiter\\ArbiterApi@ModifyChar');
Routes::post('/api/ModifyChar', 'Arbiter\\ArbiterApi@DeleteChar');
<?php

namespace App\Controllers\Arbiter\systemApi;

use App\Models\JSON;
class SystemApi
{
	function status()
	{
		$data['Return'] = true;
		
		JSON::send_json($data);
	}
}
<?php

namespace App\Controllers\Arbiter\SystemApi;

use App\Controllers\Controller;
class SystemApi extends Controller
{
	function status(): bool
	{
		$data['Return'] = true;
		
		return $this->response($data);
	}
}

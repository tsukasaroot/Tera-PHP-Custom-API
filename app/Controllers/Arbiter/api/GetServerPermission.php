<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo 'nope';
	die();
}

require_once(SQL);
require_once(JSON);


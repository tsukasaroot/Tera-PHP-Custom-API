<?php

require 'core/Database.php';
require 'core/Token.php';

use Core\Database as Database;
use Core\Token as Token;

function make_controller($name)
{
	if ($name === null) {
		echo <<<EOF
        php manager controller @name
        EOF;
		die();
	}
	file_put_contents('app/Controllers/' . $name . '.php', <<<EOF
		<?php
		
		namespace App\Controllers;
		class $name extends Controller
		{
		
		}
		EOF
	);
}

function make_model($name)
{
	if ($name === null) {
		echo <<<EOF
        php manager model @name
        EOF;
		die();
	}
	file_put_contents('app/Models/' . $name . '.php', <<<EOF
		<?php
		
		namespace App\Models;
		
		use Core\Model\Model;
		class $name extends Model
		{
			protected string \$table = '';
		}
		EOF
	);
}

function add_route($argv)
{
	if (count($argv) < 3) {
		echo <<<EOF
        php manager add_route @method,@route,@controller|null
        EOF;
		die();
	}
	for ($i = 2; $i < count($argv); $i++) {
		$array = explode(',', $argv[$i]);
		$comment = '';
		
		if (count($array) === 2)
			$array[2] = "closure: function () {}";
		else
			$array[2] = "action: '" . $array[2] . "'";
		$array[0] = strtolower($array[0]);
		
		if (count($array) === 4)
			$comment = "\n/* $array[3] */";
		
		file_put_contents('routes/api.php', <<<EOF
			$comment
			Routes::$array[0](route: '$array[1]', $array[2]);
			EOF
			, FILE_APPEND | LOCK_EX);
	}
}

function migrate(string|null $action, string|null $args)
{
	$env = parse_ini_file('.env');
	
	foreach ($env as $k => $v) {
		if (str_starts_with($k, 'db'))
			$GLOBALS['Database'][$k] = $v;
		else
			$GLOBALS[$k] = $v;
	}
	
	require 'database/Migrator.php';
	$migrator = new Migrator();
	
	switch ($action) {
		case 'refresh':
			$migrator->do_refresh();
			break;
		case 'drop':
			if (empty($args)) {
				helper();
				die();
			}
			$tables = explode(',', $args);
			$migrator->do_drop($tables);
			break;
		default:
			$migrator->do_migration();
	}
}

function authKey(string|null $action, string|null $args)
{
	$env = parse_ini_file('.env');
	
	foreach ($env as $k => $v) {
		if (str_starts_with($k, 'db'))
			$GLOBALS['Database'][$k] = $v;
		else
			$GLOBALS[$k] = $v;
	}
	
	if (!$GLOBALS['authToken']) {
		echo "authKey is not activated.";
		die();
	}
	
	$token_class = new Token();
	$db = new Database(1);
	$driver = $db->get_sql();
	
	switch ($action) {
		case 'create':
			$date = time();
			$token = uniqid(more_entropy: true);
			$sql = <<<EOF
			INSERT INTO tokens VALUES('$token',$date)
			EOF;
			if ($driver->query($sql)) {
				echo "Token added with success, token to use on extern app:\n$token";
			} else {
				echo "Error happened when inserting into table token\n";
				echo $driver->error;
			}
			break;
		default:
			helper();
			break;
	}
}

function helper()
{
	echo <<<EOF
    php manager controller - Create a new controller
    php manager model - Create a new model
    php manager add_route - Add a route to routes/api.php
    php manager migrate - Migrate all sql files inside Database/migrations/
    php manager migrate drop @table1,@table2,...
    php manager migrate refresh - Drop all tables present in Database/migrations/ then migrate them back
    EOF;
}

if (count($argv) === 1) {
	helper();
	die();
}

$call = $argv[1];

switch ($call) {
	case 'controller':
		make_controller($argv[2] ?? null);
		break;
	case 'model':
		make_model($argv[2] ?? null);
		break;
	case 'add_route':
		add_route($argv);
		break;
	case 'migrate':
		migrate($argv[2] ?? null, $argv[3] ?? null);
		break;
	case 'authKey':
		authKey($argv[2] ?? null, $argv[3] ?? null);
		break;
	default:
		helper();
		break;
}
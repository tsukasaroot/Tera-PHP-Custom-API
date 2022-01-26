<?php

namespace App\Models;

class SQL
{
	public static function query(string $query): array|null
	{
		$env = parse_ini_file('.env');
		
		$conn = new \mysqli($env['host'], $env['user'], $env['pwd'], $env['db']);
		if ($conn->connect_error) {
			$conn->close();
			die("Connection failed: ");
		}
		$result = $conn->query($query);
		$rows = $conn->affected_rows;
		$conn->close();
		return [ $result, $rows];
	}
}
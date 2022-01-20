<?php

class SQL
{
	public function __construct()
	{
		$this->env = parse_ini_file('.env');
		$this->conn = new mysqli($this->env['host'], $this->env['user'], $this->env['pwd'], $this->env['db']);
		if ($this->conn->connect_error) {
			$this->conn->close();
			die("Connection failed: ");
		}
	}
	
	private array $env;
	public mysqli $conn;
}
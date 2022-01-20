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
	
	public function close()
	{
		$this->conn->close();
	}
	
	public function open()
	{
		$this->conn = new mysqli($this->env['host'], $this->env['user'], $this->env['pwd'], $this->env['db']);
		if ($this->conn->connect_error) {
			$this->conn->close();
			die("Connection failed: ");
		}
	}
	
	public function myquery(string $q): mysqli_result|null
	{
		$this->open();
		return $this->conn->query($q);
	}
	
	private array $env;
	public mysqli $conn;
}
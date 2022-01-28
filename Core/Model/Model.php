<?php

namespace Core\Model;

class Model
{
	private string $query;
	private object|string $sql_result;
	private \mysqli $sql;
	protected string $table;
	
	public function __destruct()
	{
		$this->sql->close();
	}
	
	/* __construct database connection and table name */
	public function __construct()
	{
		$env = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . '/../.env');
		
		$this->sql = new \mysqli($env['host'], $env['user'], $env['pwd'], $env['db']);
		
		if ($this->sql->connect_error) {
			$this->sql->close();
			die("Connection failed: ");
		}
	}
	
	public function select(string|array $args = '*'): static
	{
		if (is_array($args)) {
			$this->query = 'SELECT ' . implode(',', $args) . ' FROM ' . $this->table . ' ';
		} else {
			$this->query = 'SELECT ' . $args . ' FROM ' . $this->table . ' ';
		}
		return $this;
	}
	
	public function where(string|array $left, string $cond = '=', string|int $var = null): static
	{
		if (is_array($left)) {
			$buildQuery = http_build_query($left, null, ',');
			$buildQuery = str_replace(':', '=', $buildQuery);
			$buildQuery = str_replace(',', ' OR ', $buildQuery);
			
			if (str_contains($this->query, 'WHERE'))
				$this->query .= ' OR ' . $buildQuery . ' ';
			else
				$this->query .= 'WHERE ' . $buildQuery;
		} else {
			$this->query .= 'WHERE ' . $left . $cond . $var . ' ';
		}
		return $this;
	}
	
	public function get(): object|array|bool
	{
		return $this->sql
				->query($this->query)
				->fetch_all() ?? false;
	}
	
	public function get_row(): object|array|bool
	{
		$rs = $this->sql->query($this->query);
		return $rs->fetch_assoc() ?? false;
	}
	
	public function value(): string
	{
		return $this->sql_result;
	}
}
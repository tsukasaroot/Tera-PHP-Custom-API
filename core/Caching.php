<?php

namespace Core;

use Memcache;

class Caching
{
	private Memcache $memcached;
	public bool $status = false;
	
	public function __destruct()
	{
		// TODO: Implement __destruct() method.
	}
	
	public function __construct()
	{
		$this->memcached = new Memcache();
		if (!$this->memcached->addServer($GLOBALS['MEMCACHED_HOST'], $GLOBALS['MEMCACHED_PORT'])) {
			Http::sendJson(['error' => 'Connection failed to Memcache server'], 500);
			die();
		}
		$this->status = true;
	}
	
	public function add(array $array = null, string $key = null, string $value = null): bool
	{
		$success = false;
		
		if ($array) {
			foreach ($array as $k => $item) {
				$success = $this->memcached->add($k, $item);
			}
		}
		
		if ($key && $value) {
			$success = $this->memcached->add($key, $value);
		}
		
		return $success;
	}
	
	public function get(array $array=null, string $key=null): array
	{
		$result_array = [];
		
		if ($array) {
			foreach ($array as $item) {
				$result_array[$item] = $this->memcached->get($item);
			}
		}
		
		if ($key)
			$result_array[$key] = $this->memcached->get($key);
		
		return $result_array;
	}
	
	public function delete(array $array=null, string $key=null)
	{
		if ($array)
		{
			foreach ($array as $item) {
				$this->memcached->delete($item);
			}
		}
		
		if ($key)
			$this->memcached->delete($key);
	}
	
	public function flush()
	{
		$this->memcached->flush();
	}
}
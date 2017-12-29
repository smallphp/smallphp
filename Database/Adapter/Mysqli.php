<?php
namespace Smallphp\Database\Adapter;

class Mysqli implements \Smallphp\Database\Adapter  {
	
	private $config = array();

	private $identity = null;

	private $resource = null;

	public function __construct($config) {
		$this->config = $config;
	}

	public function query($sql) {
		$this->connect();
		$this->resource = mysqli_query($this->identity, $sql);
		if ($this->resource === FALSE) {
			throw new \Exception(mysqli_error($this->identity), mysqli_errno($this->identity));
		}
		if ($this->resource instanceof \mysqli_result) {
			return new \Smallphp\Database\Adapter\Mysqli\Result($this->resource);
		} else {
			echo 'i';
		}
	}

	public function lastInsertId() {
	
	}

	public function getErrorCode() {
	
	}

	public function getErrorInfo() {
	
	}
		

	public function __destruct() {
		if ($this->identity) {
			mysqli_close($this->identity);
		}
	}

	/**
	* 链接数据库
	*/
	private function connect() {
		if ($this->identity === NULL) {
			$this->identity = @new \Mysqli($this->config['dbhost'], $this->config['dbuser'], $this->config['passwd'], $this->config['dbname']);
			if (!$this->identity) {
				throw new \Exception(mysqli_connect_error(), mysqli_connect_errno());
			}
		}
	}
}
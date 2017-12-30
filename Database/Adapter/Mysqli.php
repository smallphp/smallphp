<?php
namespace Smallphp\Database\Adapter;

class Mysqli implements \Smallphp\Database\Adapter  {
	
	/**
	* 配置对象
	*/
	private $config = array();
	
	/**
	* 链接句柄
	*/
	private $identity = null;
	
	/**
	* 资源对象
	*/
	private $resource = null;
	
	/**
	* 错误编码
	*/
	private $errorCode = 0;

	/**
	* 错误信息
	*/
	private $errorInfo = '';
	
	/**
	* 初始化配置
	*/
	public function __construct($config) {
		$this->config = $config;
	}
	
	/**
	* 执行一条Sql语句
	*/
	public function query($sql) {
		$this->connect();
		$this->resource = mysqli_query($this->identity, $sql);
		if ($this->resource === FALSE) {
			if ($this->config['debug'] === true) {
				throw new \Exception(mysqli_error($this->identity), mysqli_errno($this->identity));
			} else {
				$this->errorCode =  mysqli_errno($this->identity);
				$this->errorInfo =  mysqli_error($this->identity);
			}
		}
		if ($this->resource instanceof \mysqli_result) {
			return new \Smallphp\Database\Adapter\Mysqli\Result($this->resource);
		} else {
			return true;
		}
	}
	
	/**
	* 获取最后插入自增id
	*/
	public function lastInsertId() {
		$this->connect();
		return \mysqli_insert_id($this->identity);
	}
	
	/**
	* 获取错误编码
	*/
	public function getErrorCode() {
		return $this->errorCode;
	}
	
	/**
	* 获取错误信息
	*/
	public function getErrorInfo() {
		return $this->errorInfo;
	}

	/**
	* 关闭链接
	*/
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
				if ($this->config['debug'] === true) {
					throw new \Exception(mysqli_connect_error(), mysqli_connect_errno());
				}
			}
		}
		return $this->identity;
	}
}
<?php
namespace Smallphp;

class Database {
	
	/**
	* 适配器对象
	*/
	private $adapter = null;
	
	/**
	* 初始化适配器
	*/
	public function __construct() {
		$config = \Smallphp\Di::get('config')->load('database');
		if (!$this->adapter) {
			$this->adapter = new $config['adapter']($config);
		}
	}
	
	/**
	* 执行一条select语句
	*/
	public function select($sql) {
		return $this->adapter->query($sql);
	}
	
	/**
	* 执行一条insert语句
	*/
	public function insert($sql) {
		return $this->adapter->query($sql);
	}
	
	/**
	* 执行一条delete语句
	*/
	public function delete($sql) {
		return $this->adapter->query($sql);
	}
	
	/**
	* 执行一条update语句
	*/
	public function update($sql) {
		return $this->adapter->query($sql);
	}

	/**
	* 获取最后插入自增id
	*/
	public function lastInsertId() {
		return $this->adapter->lastInsertId();
	}
	
	/**
	* 获取错误编码
	*/
	public function getErrorCode() {
		return $this->adapter->getErrorCode();
	}

	/**
	* 获取错误信息
	*/
	public function getErrorInfo() {
		return $this->adapter->getErrorInfo();
	}
}
<?php
namespace Smallphp\Mvc;

abstract class Model {

	private $tables = array();

	protected $db = NULL;

	public function __construct() {
		if ($this->db === NULL) {
			$this->db = new \Smallphp\Database();	
		}
		$class = get_class($this);
		if (!isset($this->tables[$class])) {
			$this->tables[$class] = new \StdClass();
			$this->tables[$class]->offset = $this->tables[$class]->size = 0;
			$this->tables[$class]->table =  strtolower(preg_replace('`.+(?<=['.preg_quote('\\').'])`', '', $class));
		}
	}
	
	/**
	* 一个字段
	*/
	public function getOne() {

	}
	
	/**
	* 一条结果
	*/
	public function getRow() {

	}
	
	/**
	* 所有结果
	*/
	public function getAll() {
		$class = get_class($this);
		$size = $this->tables[$class]->size;
		$offset = $this->tables[$class]->offset;
		$limit = '';
		if ($size > 0) $limit = "limit {$offset}, {$size}";
		return $this->db->select("SELECT * FROM {$this->table()} {$limit}")->fetchAll();
	}
	
	/**
	* 获取总数
	*/
	public function getCount() {
		$result = $this->db->select("SELECT count(*) FROM {$this->table()}")->fetchAll();
		if ($result) {
			return $result[0]['count(*)'];
		}
		return 0;
	}
	
	/**
	* limit实现
	*/
	public function limit($offset, $size) {
		$class = get_class($this);
		$this->tables[$class]->size = $size;
		$this->tables[$class]->offset = $offset;
		return $this;
	}

	/**
	* 获取表名
	*/
	private function table() {
		$class = get_class($this);
		return $this->tables[$class]->table;
	}
}
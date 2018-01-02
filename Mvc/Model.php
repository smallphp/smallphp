<?php
namespace Smallphp\Mvc;

abstract class Model {
	
	/**
	* 模型实例
	*/
	private $property =  NULL;

	/**
	* 数据库实例
	*/
	protected $db = NULL;

	/**
	* 初始化model
	*/
	public function __construct() {
		if ($this->db === NULL) {
			$this->db = new \Smallphp\Database();	
		}
		$class = get_class($this);
		if ($this->property == NULL) {
			$this->property = new \StdClass();
			$this->property->query = [];
			$this->property->query['where'] = array();
			$this->property->query['offset'] = $this->property->query['size'] = 0;
			if (!isset($this->table)) {
				$this->table =  strtolower(preg_replace('`.+(?<=['.preg_quote('\\').'])`', '', $class));
			}
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
		$size = $this->property->query['size'];
		$offset = $this->property->query['offset'];
		$limit = '';
		if ($size > 0) $limit = "limit {$offset}, {$size}";
		return $this->db->select("SELECT * FROM {$this->table} {$this->property->query['where']} {$limit}")->fetchAll();
	}
	
	/**
	* 获取总数
	*/
	public function getCount() {
		$result = $this->db->select("SELECT count(*) FROM {$this->table}")->fetchAll();
		if ($result) {
			return $result[0]['count(*)'];
		}
		return 0;
	}
	
	/**
	* 条件实现
	*/
	public function where($condition) {
		if ($condition && is_array($condition)) {
			$this->property->query['where'] =  'WHERE '. preg_replace('/^(and|or)/i', '', trim($this->buildCondition($condition)));
		} else {
			$this->property->query['where'] = '';
		}
		return $this;
	}
	
	/**
	* limit实现
	*/
	public function limit($offset, $size) {
		$this->property->query['size'] = $size;
		$this->property->query['offset'] = $offset;
		return $this;
	}
	
	public function __destruct() {
		$this->property->query = array();
	}


	private function buildCondition($condition, $andor='and') {
		$where = '';
		$andor = strtoupper($andor);
		foreach ($condition as $k=>$v) {
			if (is_array($v)) {
					if (preg_match('/[\d]+/', $k)) {
						$where .= $andor.' ('.$this->buildCondition($v, '').') ';
					} else {
						$where .= $this->buildQuery($v, $k);
					}
			} else {
				$field = $k;
				$symbol = '=';
				if (preg_match('`(?<=\[)(?:>[=]?|<[=]?)(?=[\]])`i', $k, $matchs)) {
					$symbol = $matchs[0];
					$field = preg_replace('`\['.$symbol.'\]`', '', $k);
				}
				if( ! is_int($v)) {
					$v = '"'.$v.'"';
				}
				if ($andor == '0') {
					$where.= ' `'.$field.'` '.$symbol.$v.' ';
				} else {
					$where.= ' '.$andor.' `'.$field.'` '.$symbol.' '.$v.' ';
				}
			}
			
		}
		return $where;
	}
}
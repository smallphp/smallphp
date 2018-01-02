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
			$this->property->query['where'] = $this->property->query['order'] = $this->property->query['group'] = '';
			$this->property->query['offset'] = $this->property->query['size'] = 0;
			if (!isset($this->table)) {
				$this->table =  strtolower(preg_replace('`.+(?<=['.preg_quote('\\').'])`', '', $class));
			}
		}
	}
	
	/**
	* 一个字段
	*/
	public function getOne($field=0) {
		$data = $this->getRow();
		if ($data) {
			if (isset($data[$field])) {
				return $data[$field];
			}
			$fields = array_keys($data);
			if (isset($fields[$field], $data[$fields[$field]])) {
				return $data[$fields[$field]];
			}
		}
		return NULL;
	}
	
	/**
	* 一条结果
	*/
	public function getRow() {
		$data = $this->db->select("SELECT * FROM {$this->table} {$this->property->query['where']} LIMIT 1")->fetchAll();
		if ($data) {
			return $data[0];
		}
		return array();
	}
	
	/**
	* 所有结果
	*/
	public function getAll() {
		$size = $this->property->query['size'];
		$offset = $this->property->query['offset'];
		$limit = '';
		if ($size > 0) $limit = "limit {$offset}, {$size}";
		return $this->db->select("SELECT * FROM {$this->table} {$this->property->query['where']} {$this->property->query['group']} {$this->property->query['order']}  {$limit}")->fetchAll();
	}
	
	/**
	* 获取总数
	*/
	public function getCount() {
		$result = $this->db->select("SELECT count(*) FROM {$this->table} {$this->property->query['where']} LIMIT 1")->fetchAll();
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
	* 排序实现
	*/

	public function order($fields) {
		$order = '';
		if ($fields && is_array($fields)) {
			foreach ($fields as $k=>$v) {
				$order.= ($k == '0' ? '`'.$v.'`'.' ASC' : '`'.$k.'`'.' '.strtoupper($v)).',';
			}
			$this->property->query['order'] = 'ORDER BY '.rtrim($order, ',');
		}
		return $this;
	}
	
	/**
	* 分组实现
	*/
	public function group($fields) {
		$group = '';
		if ($fields && is_array($fields)) {
			foreach ($fields as $v) {
				$group.= '`'.$v.'`,';
			}
			$this->property->query['group'] = 'GROUP BY '.rtrim($group, ',');
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

	/**
	* 生成条件
	*/
	private function buildCondition($condition, $andor='and') {
		$where = '';
		$andor = strtoupper($andor);
		foreach ($condition as $k=>$v) {
			if (is_array($v)) {
				if (preg_match('/[\d]+/', $k)) {
					$where .= $andor.' ('.$this->buildCondition($v, '').') ';
				} else {
					$where .= $this->buildCondition($v, $k);
				}
			} else {
				$field = $k;
				$symbol = '=';
				if (preg_match('`(?<=\[)(?:=|>[=]?|<[=]?)(?=[\]])`i', $k, $matchs)) {
					$symbol = $matchs[0];
					$field = preg_replace('`\['.$symbol.'\]`', '', $k);
				}
				if( ! is_int($v)) {
					$v = '"'.$v.'"';
				}
				if (preg_match('/[\d]+/', $andor)) {
					$where.= ' `'.$field.'` '.$symbol.$v.' ';
				} else {
					$where.= ' '.$andor.' `'.$field.'` '.$symbol.' '.$v.' ';
				}
			}
		}
		return $where;
	}
}
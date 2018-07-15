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
	public function where($condition=array()) {
		if ($condition && is_array($condition)) {
			$this->property->query['where'] =  'WHERE '. trim($this->buildCondition($condition));
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
	
	/**
	* 创建记录
	*/
	public function insert() {
	
	}
	/**
	* 更新记录
	*/
	public function update() {
	
	}

	/**
	* 创建更新
	*/
	public function upsert() {
		
	}
	
	public function __destruct() {
		$this->property->query = array();
	}

	/**
	* 生成条件
	* simple
	* ['name'=>'a'] ----> `name` = a
	* ['name'=>'a', 'age'=>28] ----> `name` = a AND `age` = 28
	* ['name'=>'a', 'OR', 'age'=>28] ----> `name` = a OR `age` = 28
	* [['name'=>'a', 'OR', 'age'=>28]], 'AND', ['class'=>'a'] ----> (`name` = a OR `age` = 28)  AND `class` = a
	* [['name'=>'a', 'OR', 'age'=>28]], 'AND', [['class'=>'a', 'sex'=>'man']] ----> (`name` = a OR `age` = 28)  AND  (`class` = a AND `sex` = man)
	* ['age'=>'>=90','name'=>'in("a","b")'] ----> `age` >= 90 AND `name` in("a","b")
	*/
	private function buildCondition() {
		$query = '';
		foreach (func_get_args() as $key=>$param) {
			if (is_array($param)) {
				$index = 0;
				$count = count($param);
				$field = array_keys($param);
				foreach ($param as $key2=>$param2) {
					if (is_array($param2)) {
						$brackets = preg_match('/^[\d]+$/', $key2);
						if ($brackets) {
							$query.=' (';
						}
						$query.= $this->buildCondition($param2);
						if ($brackets) {
							$query.=') ';
						}
					} else {
						++$index;
						if (preg_match('/^[\d]+$/', $key2)) {
							$query.= " {$param2} ";
						} else {
							$flag = isset($field[$index]) && preg_match('/^[\d]+$/', $field[$index]); //下一个元素是否索引数据
							if (preg_match('/(>[=]?|<[=]?)(.+)/', $param2, $matchs)) { // > >= < <=
								if (!is_int($matchs[2])) {
									$matchs[2] = "'{$matchs[2]}'";
								}
								$query.= "`{$key2}` {$matchs[1]} {$matchs[2]}";
							} else if (preg_match('/(in[\s]*\(.+\))/i', $param2, $matchs)) { //in
								if (!is_int($matchs[1])) {
									$matchs[1] = "'{$matchs[1]}'";
								}
								$query.= "`{$key2}` {$matchs[1]}";
							} else {
								if (!is_int($param2)) {
									$param2 = "'{$param2}'";
								}
								$query.= "`{$key2}` = {$param2}";
							}
							if ($index < $count && !$flag) {
								$query.= ' AND ';
							}
						}
					}
				}
			} else {
				$query.= " {$param} ";
			}
		}
		return trim($query);
	}
}
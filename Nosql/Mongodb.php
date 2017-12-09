<?php
namespace Smallphp\Nosql;

class Mongodb {
	/**
	 * collection
	 */
	private $collection = '';

	/**
	 * filter
	 */
	private $filter = [];

	/**
	 * option
	 */
	private $option = [];

	/**
	 * field
	 */
	private $fields = [];

	/**
	 * manager
	 */
	private static $manager = null;

	/**
	 * dbname
	 */
	private static $dbname = '';

	/**
	 * set collection name
	 */
	public function collection($collection) {
		$this -> collection = $collection;
		return $this;
	} 

	public function field(array $fields) {
		$this -> fields['projection'] = $fields;
		return $this;
	} 

	/**
	 * set filter
	 */
	public function filter(array $filter) {
		$this -> filter = $filter;
		return $this;
	} 

	/**
	 * set option
	 */
	public function option(array $option) {
		$this -> option = $option;
		return $this;
	} 

	/**
	 * get all record
	 */
	public function fetchAll() {
		if ($this -> collection) {
			$data = \Smallphp\Nosql\Mongodb\Query :: execute(self :: __connnection(), new \MongoDB\Driver\Query($this -> filter, array_merge($this -> option, $this -> fields)), self :: $dbname, $this -> collection);
			$this -> filter = $this -> option = $this -> fields = [];
			return $data;
		} 
		return array();
	} 

	/**
	 * get row record
	 */
	public function fetchRow() {
		if ($this -> collection) {
			$data = \Smallphp\Nosql\Mongodb\Query :: execute(self :: __connnection(), new \MongoDB\Driver\Query($this -> filter, array_merge($this -> option, $this -> fields, ['limit' => 1])), self :: $dbname, $this -> collection);
			$this -> filter = $this -> option = $this -> fields = [];
			if (isset($data[0]) && $data[0]) {
				return $data[0];
			} 
		} 
		return array();
	} 

	/**
	 * get count number
	 */
	public function getCount() {
		$cmd = new \MongoDB\Driver\Command([
			'count' => $this -> collection,
			'query' => $this -> filter,
			]);
		$res = self :: __connnection() -> executeCommand(self :: $dbname, $cmd) -> toArray();
		$this -> option = $this -> filter = [];
		if (isset($res[0], $res[0] -> n)) {
			return $res[0] -> n;
		} 
		return 0;
	} 

	/**
	 * insert
	 */
	public function insert($data) {
		$bulkWrite = new \MongoDB\Driver\BulkWrite();
		$bulkWrite -> insert($data);
		return \Smallphp\Nosql\Mongodb\Query :: execute(self :: __connnection(), $bulkWrite, self :: $dbname, $this -> collection);
	} 

	/**
	 * update
	 */
	public function update($data) {
		$bulkWrite = new \MongoDB\Driver\BulkWrite();
		$bulkWrite -> update($this -> filter, array('$set' => $data), array('multi' => true, 'upsert' => false));
		return \Smallphp\Nosql\Mongodb\Query :: execute(self :: __connnection(), $bulkWrite, self :: $dbname, $this -> collection);
	} 

	/**
	 * delete
	 */
	public function delete() {
		$bulkWrite = new \MongoDB\Driver\BulkWrite();
		$bulkWrite -> delete($this -> filter);
		return \Smallphp\Nosql\Mongodb\Query :: execute(self :: __connnection(), $bulkWrite, self :: $dbname, $this -> collection);
	} 

	/**
	 * __connnection
	 */
	private function __connnection() {
		if (self :: $manager === null) {
			$config = \Smallphp\Di :: get('config') -> load('mongodb');
			self :: $dbname = $config['dbname'];
			if (isset($config['dbuser'], $config['passwd']) && $config['dbuser'] && $config['passwd']) {
				$uri = sprintf('mongodb://%s:%s@%s:%s/%s', $config['dbuser'], $config['passwd'], $config['dbhost'], $config['dbport'], $config['dbname']);
			} else {
				$uri = sprintf('mongodb://%s:%s', $config['dbhost'], $config['dbport']);
			} 
			self :: $manager = new \MongoDB\Driver\Manager($uri);
		} 
		return self :: $manager;
	} 

	public function __destruect() {
		$this -> filter = $this -> option = $this -> fields = [];
	} 
} 

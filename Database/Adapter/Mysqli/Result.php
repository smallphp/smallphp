<?php
namespace Smallphp\Database\Adapter\Mysqli;

class Result {
	
	private $resource =  null;

	public function __construct(&$resource) {
		$this->resource = $resource;
	}

	public function fetchAll() {
		$data = array();
		while ($row = $this->resource->fetch_assoc()) {
			$data[] = $row;
		}
		return $data;
	}

	public function __destruct() {
		if(is_object($this->resource)) {
			mysqli_free_result($this->resource);
		}
	}
}
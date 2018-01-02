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
}
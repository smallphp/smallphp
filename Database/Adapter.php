<?php
namespace Smallphp\Database;

interface Adapter {

	public function select($sql);

	public function update($sql);

	public function insert($sql);

	public function delete($sql);
}
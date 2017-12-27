<?php
namespace Smallphp;

implements Database {

	public function select($sql);

	public function update($sql);

	public function insert($sql);

	public function delete($sql);
}
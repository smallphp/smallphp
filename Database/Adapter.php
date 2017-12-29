<?php
namespace Smallphp\Database;

interface Adapter {
	
	public function query($sql);

	public function lastInsertId();

	public function getErrorCode();

	public function getErrorInfo();
}
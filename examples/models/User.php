<?php
namespace App\Model;

class User extends \Smallphp\Mvc\Model {

	public $table = 'user';

	public function __construct() {
		parent::__construct();
	}
}
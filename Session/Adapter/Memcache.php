<?php
namespace Smallphp\Session\Adapter;
use \Smallphp\Cache\Adapter\Memcache AS Cache;

class Memcache implements \Smallphp\Session\Adapter {
	
	/**
	* @保存时间
	*/
	public $lifetime = 1440;

	/**
	* @会话保存id
	*/
	private $saveid = '';
	
	/**
	* 会话开始
	*+------------
	* @param string $savepath
	*/
	public function open($savepath='', $sessname='') {
		$this->saveid = $sessname.'_';
		return true;
	}
	
	/**
	* 会话读取
	*+------------------
	* @param string $sid
	* @return mixed
	*/
	public function read($sid='') {
		return Cache::factory()->get($this->sid($sid));
	}
	
	/**
	* 会话写入
	*+------------------
	* @param string $sid
	* @param mixed $data
	*/
	public function write($sid='', $data=NULL) {
		return Cache::factory()->set($this->sid($sid), $data, 0, $this->lifetime);
	}
	
	/**
	* 会话关闭
	*/
	public function close() {
		return true;
	}
	
	/**
	* 会话注销
	*/
	public function destroy($sid) {
	
	}
	
	/**
	* 会话回收
	*/
	public function gc() {
	
	}	
	
	/**
	* 获取sid
	*+------------------
	* @param string $sid
	* @return string
	*/
	private function sid($sid) {
		return strtoupper($this->saveid.$sid);
	}
}
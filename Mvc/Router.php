<?php
namespace Smallphp\Mvc;

class Router {
	/**
	 * 路由处理
	 */
	public function execute($url) {
		$url = trim($url, '/');
		$matchs = null;
		$config = \Smallphp\Di :: get('config') -> load('router');
		if ($url) {
			foreach ($config as $v) {
				$pattern = preg_replace(array('`(?<=[)]{2})`', '`(?=[<])`', '`(?<=[>])(?=[()])`', '`(?<=[(])(?![?])`', '`[.]`'), array('?', '?', '[\w]+', '?:', '\.'), $v[0]);
				if (isset($v[1]) && is_array($v[1])) {
					foreach ($v[1] as $name => $rule) {
						$pattern = preg_replace('`(?<=' . $name . '>)' . preg_quote('[\w]+') . '(?=[)])`', $rule, $pattern);
					} 
				} 
				preg_match('`^' . $pattern . '$`', $url, $matchs);
				if ($matchs) {
					if (isset($v[2]) && is_array($v[2])) {
						$matchs = array_merge($v[2], $matchs);
					} 
					break;
				} 
			} 
			if ($matchs) {
				foreach ($matchs as $k => $v) {
					if (!preg_match('/^[a-z]+$/i', $k)) {
						unset($matchs[$k]);
					} 
				} 
			} else {
				throw new \Exception('Routing failure');
			} 
		} else {
			$default = $config[count($config)-1];
			if (isset($default[2]) && is_array($default[2])) {
				return $default[2];
			} 
		} 
		return $matchs;
	} 
} 

<?php

namespace Smallphp;
class Di
{
    /**
     * $services
     */
    public static $services = [];

    /**
     * 注册服务组件
     * +----------------------
     *
     * @param string $id
     * @param string $service
     * @parma bool $shared
     */
    public static function set($id, $service, $shared = true)
    {
        if (is_object($service) && !is_callable($service)) {
            $callback = function () use ($service) {
                return $service;
            };
        } else if (is_callable($service)) {
            $callback = $service;
        } else if (is_string($service)) {
            $callback = function () use ($service) {
                return new $service;
            };
        } else if (is_array($service)) {
            // array
        } else {
            throw new \Exception();
        }
        self:: $services[$id] = array('callback' => $callback,
                                      'isshared' => $shared,
        );
    }

    /**
     * 获取注册组件
     * +-----------------
     *
     * @parma string $id
     * @return mixed
     */
    public static function get($id)
    {
        if (isset(self:: $services[$id])) {
            if (self:: $services[$id]['isshared'] && isset(self:: $services[$id]['instance'])) { // 共享实例
                return self:: $services[$id]['instance'];
            }
            if (self:: $services[$id]['isshared']) {
                return self:: $services[$id]['instance'] = call_user_func(self:: $services[$id]['callback']);
            }
            return call_user_func(self:: $services[$id]['callback']);
        }
    }

    /**
     * construct
     */
    private function __construct()
    {
    }

    /**
     * clone
     */
    private function __clone()
    {
    }
} 

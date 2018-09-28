<?php

namespace Smallphp;

abstract class Session
{

    /**
     * start session
     *
     * @param \Smallphp\Session\Adapter $adapter
     * @param int $lifetime
     * @return void
     */
    public static function start(\Smallphp\Session\Adapter &$adapter, $lifetime = 1440)
    {
        //禁止多次启动
        if (empty(session_id())) {
            $adapter->lifetime = $lifetime;
            session_set_save_handler(
                array($adapter, 'open'),
                array($adapter, 'close'),
                array($adapter, 'read'),
                array($adapter, 'write'),
                array($adapter, 'destroy'),
                array($adapter, 'gc')
            );
            ini_set('session.save_handler', 'files');
            session_start();
            setcookie(session_name(), session_id(), time() + $lifetime, '/');
        }
    }
}
<?php

namespace Smallphp\Session\Adapter;

use \Smallphp\Cache\Adapter\Redis AS Cache;

class Redis implements \Smallphp\Session\Adapter
{

    /**
     * @保存时间
     */
    public $lifetime = 1440;

    /**
     * @会话保存id
     */
    private $saveid = '';

    public function open($savepath = '', $sessname = '')
    {
        $this->saveid = $sessname . '_';
        return true;
    }

    public function read($sid = '')
    {
        return Cache::factory()->get($this->sid($sid));
    }

    public function write($sid = '', $data = '')
    {
        return Cache::factory()->setex($this->sid($sid), $this->lifetime, $data);
    }

    public function close()
    {
        return true;
    }

    public function destroy($sid)
    {
        return Cache::factory()->delete($this->saveid . $sid);
    }

    public function gc()
    {

    }

    /**
     * 获取sid
     *+------------------
     * @param string $sid
     * @return string
     */
    private function sid($sid)
    {
        return strtoupper($this->saveid . $sid);
    }
}
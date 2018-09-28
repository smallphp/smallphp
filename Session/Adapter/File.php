<?php

namespace Smallphp\Session\Adapter;

class File implements \Smallphp\Session\Adapter
{

    /**
     * @保存时间
     */
    public $lifetime = 1440;

    /**
     * @会话保存id
     */
    private $saveid = '';

    /**
     * @会话保存目录
     */
    private $savepath = '';

    public function open($savepath = '', $sessname = '')
    {
        $this->saveid = $sessname . '_';
        $this->savepath = $savepath;
        return true;
    }

    public function read($sid = '')
    {
        $file = $this->sid($sid);
        if (is_file($file) && is_readable($file)) {
            return file_get_contents($this->sid($sid));
        }
        return NULL;
    }

    public function write($sid = '', $data = '')
    {
        return file_put_contents($this->sid($sid), $data);
    }

    public function close()
    {
        return true;
    }

    public function destroy($sid)
    {
        $file = $this->sid($sid);
        if (is_file($file) && is_writeable($file)) {
            unset($file);
        }
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
        $savepath = $this->savepath . DIRECTORY_SEPARATOR . substr($sid, 0, 1) . DIRECTORY_SEPARATOR;
        if (!is_dir($savepath)) mkdir($savepath, 0777, TRUE);
        return $savepath . strtoupper($this->saveid . $sid);
    }
}
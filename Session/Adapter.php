<?php

namespace Smallphp\Session;

interface Adapter
{

    /**
     * open session
     * @parma string $savepath
     * @param string $sessname
     */
    public function open($savepath = '', $sessname = '');

    /**
     * read session
     * @param string $sessid
     */
    public function read($sid);

    public function write($sid, $data);

    public function close();

    public function destroy($sid);

    public function gc();
}
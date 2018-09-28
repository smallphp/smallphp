<?php

namespace Smallphp\Mvc;

class Url
{
    /**
     *
     * @string
     */
    private static $baseurl = '';

    /**
     * 设置baseurl
     * +----------------------
     *
     * @param string $baseurl
     * @reurn true
     */
    public static function setBaseUrl($baseurl)
    {
        if (self:: $baseurl == '') {
            self:: $baseurl = $baseurl;
        }
        return true;
    }

    /**
     * 获取baseurl
     * +--------------
     *
     * @param void $
     * @return string
     */
    public static function getbaseUrl()
    {
        if (self:: $baseurl) {
            return self:: $baseurl;
        } else if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        } else {
            return $_SERVER['SERVER_NAME'];
        }
    }

    /**
     * 获取pathinfo
     * +--------------
     *
     * @param void $
     * @return string
     */
    public static function getPathInfo()
    {
        return preg_replace(array('/.+\.php/i', '/[?]?(?<=[?]).+/'), '', $_SERVER['REQUEST_URI']);
    }

    /**
     * 生成pathinfo
     * +------------------------
     *
     * @param mixed $segments
     * @param string $suffix
     * @param string $separator
     * @return string
     */
    public static function setPahtInfo($segments, $suffix = '', $separator = '/')
    {
        if (is_array($segments)) {
            return self:: getBaseUrl() . '/' . implode($separator, $segments) . $suffix;
        }
        return self:: getBaseUrl() . $segments;
    }
} 

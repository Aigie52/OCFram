<?php

namespace OCFram;


class HTTPResponse
{
    protected $page;

    public function addHeader($header)
    {
        header($header);
    }

    public function redirect($location)
    {
        header('Location: ' . $location);
        exit;
    }

    public function redirect404()
    {

    }

    public function send()
    {
        exit($this->page->getGeneratedPage());
    }

    public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        $this->setCookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public function setPage(Page $page)
    {
        $this->page = $page;
    }
}

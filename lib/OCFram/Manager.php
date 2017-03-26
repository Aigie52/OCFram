<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 26/03/2017
 * Time: 14:13
 */

namespace OCFram;


class Manager
{
    protected $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 25/03/2017
 * Time: 14:50
 */

namespace OCFram;


abstract class ApplicationComponent
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function app()
    {
        return $this->app;
    }
}

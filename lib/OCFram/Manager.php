<?php
namespace OCFram;

abstract class Manager
{
    use CacheableData;

    protected $dao;
    public $entityName;

    public function __construct($dao)
    {
        $this->dao = $dao;
        $this->entityName = explode('Manager', explode('Model\\', get_class($this))[1])[0];
    }
}

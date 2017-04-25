<?php

namespace OCFram;


trait CacheableData
{
    protected function updateCache($id)
    {
        $cache = new Cache();
        $cache->delete($this->entityName, $id);
    }
}

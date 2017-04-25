<?php

namespace OCFram;


trait CacheableData
{
    protected function updateCache($id)
    {
        $cache = new DataCache();
        $cache->delete($this->entityName, $id);
    }
}

<?php

namespace OCFram;


class ViewCache extends Cache
{
    /**
     * Créer le cache en fonction du type (vue), du nom de la vue, du contenu et de la durée (facultatif)
     * @param string $type
     * @param string $name
     * @param $content
     * @param null|string $duration
     */
    public function createCache($type, $name, $content = null, $duration = null)
    {
        $this
            ->setType($type)
            ->setDirname()
            ->setDuration($duration)
            ->setFilename($name);

        if (null !== $content) {
            $this->write($this->filename, $content);
        }
    }

    public function addContent($content)
    {
        $this->write($this->filename, $content);
    }

    public function start()
    {
        ob_start();
    }

    public function end()
    {
        $content = ob_get_clean();
        echo $content;
        $this->addContent($content);
    }
}

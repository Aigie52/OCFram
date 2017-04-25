<?php

namespace OCFram;


class Cache
{
    protected $dirname;
    protected $duration;
    protected $type;
    protected $filename;

    /**
     * @param $type string
     * @return Cache
     */
    protected function setType($type)
    {
        if(!is_string($type)) {
            throw new \InvalidArgumentException('Le type doit être une chaîne de caractères valide.');
        }
        $this->type = strtolower($type);
        return $this;
    }

    /**
     * @return Cache
     */
    protected function setDirname()
    {
        $typeDir = ($this->type === 'view') ? 'views' : 'data';
        $dir = __DIR__.'\\..\\..\\tmp\\cache\\'.$typeDir;
        if(!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->dirname = $dir;
        return $this;
    }

    /**
     * @param $name string
     * @return string
     */
    protected function setFilename($name)
    {
        if(!is_string($name)) {
            throw new \InvalidArgumentException('Le nom de la vue doit être une chaine de caractères valide.');
        }
        return $this->filename = strtolower($name);
    }

    /**
     * @return string
     */
    public function filename()
    {
        return $this->filename;
    }

    /**
     * @param $duration string|null
     * @return Cache
     */
    protected function setDuration($duration)
    {
        // Si la durée est explicitement précisée, alors on l'applique
        if(is_string($duration)) {
            $this->duration = $duration;
        }

        // Sinon, on la récupère dans le fichier config.xml en fonction du type de données que l'on veut mettre en cache
        $xmlConfigFile = new \DOMDocument;
        $xmlConfigFile->load(__DIR__.'/../../App/config.xml');

        $items = $xmlConfigFile->getElementsByTagName('item');
        foreach ($items as $item) {
            if($item->getAttribute('name') === $this->type) {
                $this->duration = $item->getAttribute('lifetime');
            }
        }

        return $this;
    }

    /**
     * @return int timestamp
     */
    protected function expiresAt()
    {
        $lifetime = \DateInterval::createFromDateString($this->duration);
        $expiresAt = new \DateTime();
        $expiresAt->add($lifetime);

        return $expiresAt->getTimestamp();
    }

    /**
     * Créer le cache en fonction du type, du nom de la vue, du contenu et de la durée (facultatif)
     * @param string $type
     * @param string $name
     * @param $content
     * @param null|string $duration
     */
    public function createCache($type, $name, $content, $duration = null)
    {
        $this
            ->setType($type)
            ->setDirname()
            ->setDuration($duration)
            ->setFilename($name);

        $this->write($this->filename, $content);
    }

    /**
     * Ecrit le fichier de cache
     * @param $filename
     * @param $content
     * @return bool|int
     */
    protected function write($filename, $content)
    {
        $file = $this->dirname.'\\'.$filename;
        return file_put_contents($file, serialize([$this->expiresAt(), $content]));
    }


    /**
     * Lit le fichier demandé
     * @param $type
     * @param $name
     * @return bool
     */
    public function read($type, $name)
    {
        $filename = $this
            ->setType($type)
            ->setDirname()
            ->setFilename($name);

        $file = $this->dirname.'\\'.$filename;

        if(!file_exists($file)) {
            return false;
        }

        $data = unserialize(file_get_contents($file));
        $expiresAt = $data[0];

        // Si la date de validité du fichier est dépassé, alors on le supprime
        if($expiresAt < time()) {
            $this->delete($type, $name);
            return false;
        }

        return $data[1];
    }

    /**
     * Supprime le fichier concerné du cache
     * Supprime le fichier
     * @param $type string
     * @param $name string
     * @internal param int $id
     * @internal param $filename
     */
    public function delete($type, $name)
    {
        $filename = $this
            ->setType($type)
            ->setDirname()
            ->setFilename($name);

        $file = $this->dirname.'\\'.$filename;

        if(file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Supprime tous les fichiers du cache
     */
    public function clear()
    {
        $dir = __DIR__.'\\..\\..\\tmp\\cache\\';
        $files = glob($dir.'*');
        foreach ($files as $file) {
            unlink($file);
        }
    }

}

<?php

namespace OCFram;


class Cache
{
    private $dirname;
    private $duration;
    private $buffer;
    private $type;

    /**
     * @param string $type
     * @return Cache
     */
    private function setType($type)
    {
        if(!is_string($type)) {
            throw new \InvalidArgumentException('Le type doit être une chaîne de caractères valide.');
        }
        $this->type = $type;
        return $this;
    }

    /**
     * @return Cache
     */
    private function setDirname()
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
     * @param int $id
     * @return string
     */
    private function setFilename($id)
    {
        if($this->type === 'Comments') {
            return $this->type.'-news-'.(int) $id;
        }
        return $this->type.'-'.(int) $id;
    }

    /**
     * @param string $duration
     * @return Cache
     */
    private function setDuration($duration)
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
    private function expiresAt()
    {
        $lifetime = \DateInterval::createFromDateString($this->duration);
        $expiresAt = new \DateTime();
        $expiresAt->add($lifetime);

        return $expiresAt->getTimestamp();
    }

    /**
     * Créer le cache en fonction du type (vue/news), de l'id pour un fichier unique, du contenu et de la durée (facultatif)
     * @param string $type
     * @param int $id
     * @param $content
     * @param null|string $duration
     */
    public function createCache($type, $id, $content, $duration = null)
    {
        $this
            ->setType($type)
            ->setDirname()
            ->setDuration($duration)
            ->write($this->setFilename($id), $content);
    }

    /**
     * Ecrit le fichier de cache
     * @param $filename
     * @param $content
     * @return bool|int
     */
    private function write($filename, $content)
    {
        $file = $this->dirname.'\\'.$filename;

        return file_put_contents($file, serialize([$this->expiresAt(), $content]));
    }

    /**
     * Lit le fichier en cache et renvoie le contenu si celui-ci existe
     * @param $type
     * @param $id
     * @return bool
     */
    public function read($type, $id)
    {
        $filename = $this
            ->setType($type)
            ->setDirname()
            ->setFilename($id);

        $file = $this->dirname.'\\'.$filename;

        if(!file_exists($file)) {
            return false;
        }

        $data = unserialize(file_get_contents($file));
        $expiresAt = $data[0];

        // Si la date de validité du fichier est dépassé, alors on le supprime
        if($expiresAt < time()) {
            $this->delete($filename);
            return false;
        }

        return $data[1];
    }

    /**
     * Supprime le fichier
     * @param $type string
     * @param $id int
     * @internal param $filename
     */
    public function delete($type, $id)
    {
        $filename = $this
            ->setType($type)
            ->setDirname()
            ->setFilename($id);

        $file = $this->dirname.'\\'.$filename;

        if(file_exists($file)) {
            unlink($file);
        }
    }
}

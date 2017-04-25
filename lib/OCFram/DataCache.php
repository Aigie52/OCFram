<?php

namespace OCFram;


class DataCache extends Cache
{
    /**
     * @param int $id
     * @return string
     */
    protected function setFilename($id)
    {
        $this->filename = ($this->type === 'news') ? 'news-'.(int) $id : 'comments-news-'. (int) $id;
        return $this->filename;
    }

    /**
     * Créer le cache en fonction du type (comments/news), de l'id pour un fichier unique, du contenu et de la durée (facultatif)
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
            $this->deleteExpired($type, $id);
            return false;
        }

        return $data[1];
    }

    /**
     * Supprime le fichier en cas d'ajout, de modification, de suppression
     * Supprime aussi les autres fichiers concernés par l'ajout, la modification ou la suppression d'une news
     * @param $type string
     * @param $id int
     * @internal param $filename
     */
    public function delete($type, $id)
    {
        $files = [];

        $filename = $this
            ->setType($type)
            ->setDirname()
            ->setFilename($id);

        $mainFile = $this->dirname.'\\'.$filename;
        $files[] = $mainFile;

        // Si le cache à supprimer concerne une news, alors il faut supprimer les vues d'index et éventuellement les commentaires
        if($this->type === 'news') {
            $viewsDir = __DIR__.'\\..\\..\\tmp\\cache\\views\\';
            $viewsFiles = glob($viewsDir.'*');
            foreach ($viewsFiles as $viewsFile) {
                $files[] = $viewsFile;
            }

            $commentsFile = $this->dirname.'\\comments-'.$filename;
            $files[] = $commentsFile;
        }

        foreach ($files as $file) {
            if(file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Supprime le fichier si il a expiré
     * @param $type string
     * @param $id int
     */
    public function deleteExpired($type, $id)
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

<?php

namespace Model;

use Entity\News;
use OCFram\Manager;

abstract class NewsManager extends Manager
{
    /**
     * Méthode retournant une liste de news demandée
     * @param int $debut Première news à sélectionner
     * @param int $limite Nombre de news à sélectionner
     * @return array La liste des news
     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * Méthode retournant une news précise
     * @param $id
     * @return News
     */
    abstract public function getUnique($id);

    /**
     * Méthode renvoyant le nombre de news
     * @return int
     */
    abstract public function count();

    /**
     * Méthode permettant d'ajouter une news
     * @param News $news
     * @return void
     */
    abstract protected function add(News $news);

    /**
     * Méthode permettant de modifier une news
     * @param News $news
     * @return void
     */
    abstract protected function modify(News $news);

    /**
     * Méthode permettant d'enregistrer une news
     * @param News $news
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(News $news)
    {
        if($news->isValid()) {
            $news->isNew() ? $this->add($news) : $this->modify($news);
        } else {
            throw new \RuntimeException('La news doit être valide pour être enregistrée.');
        }
    }

    /**
     * Méthode permettant de supprimer une news
     * @param $id int
     * @return void
     */
    abstract public function delete($id);
}

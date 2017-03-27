<?php

namespace Model;

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
}

<?php

namespace Model;

class NewsManagerPDO extends NewsManager
{

    /**
     * Méthode retournant une liste de news demandée
     * @param int $debut Première news à sélectionner
     * @param int $limite Nombre de news à sélectionner
     * @return array La liste des news
     */
    public function getList($debut = -1, $limite = -1)
    {
        $sql = 'SELECT * FROM news ORDER BY id DESC';

        if($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }

        $requete = $this->dao->query($sql);
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $requete->fetchAll();

        foreach ($listeNews as $news) {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        $requete->closeCursor();

        return $listeNews;
    }

    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT * FROM news WHERE id = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        if($news = $requete->fetch()){
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));

            return $news;
        }

        return null;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 27/03/2017
 * Time: 12:08
 */

namespace Model;


use Entity\Comment;
use OCFram\Manager;

abstract class CommentsManager extends Manager
{
    /**
     * Méthode permettant l'ajout d'un commentaire
     * @param Comment $comment
     * @return void
     */
    abstract protected function add(Comment $comment);

    /**
     * Méthode permettant de sauvegarder le commentaire
     * @param Comment $comment
     */
    public function save(Comment $comment)
    {
        if($comment->isValid()) {
            $comment->isNew() ? $this->add($comment) : $this->modifiy($comment);
        } else {
            throw new \RuntimeException('Le commentaire doit être valide pour être enregistré.');
        }
    }

    /**
     * Méthode permettant de récupérer la liste des commentaires liés à une news
     * @param $news
     * @return array
     */
    abstract public function getListOf($news);

}

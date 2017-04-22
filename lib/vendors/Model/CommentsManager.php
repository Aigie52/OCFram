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

    /**
     * Méthode permettant de modifier un commentaire
     * @param Comment $comment
     * @return void
     */
    abstract protected function modify(Comment $comment);

    /**
     * Méthode permettant d'obtenir un commentaire spécifique
     * @param $id
     * @return Comment
     */
    abstract public function get($id);

    /**
     * Méthode permettant de supprimer un commentaire
     * @param $id
     * @return void
     */
    abstract public function delete($id);

    /**
     * Méthode permettant de supprimer tous les commentaires liés à une news
     * @param $news
     * @return void
     */
    abstract public function deleteFromNews($news);
}

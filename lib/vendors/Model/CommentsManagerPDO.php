<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 27/03/2017
 * Time: 12:14
 */

namespace Model;


use Entity\Comment;

class CommentsManagerPDO extends CommentsManager
{

    protected function add(Comment $comment)
    {
        $q = $this->dao->prepare('INSERT INTO comments SET news = :news, auteur = :auteur, contenu = :contenu, date = NOW()');
        $q->bindValue(':news', $comment->news(), \PDO::PARAM_INT);
        $q->bindValue(':auteur', $comment->auteur());
        $q->bindValue(':contenu', $comment->contenu());

        $q->execute();

        $comment->setId($this->dao->lastInsertId());
    }

    public function getListOf($news)
    {
        if(!ctype_digit($news)) {
            throw new \InvalidArgumentException('L\èidentifiant de la news doit être un nombre entier valide.');
        }

        $q = $this->dao->prepare('SELECT * FROM comments WHERE news = :news');
        $q->bindValue(':news', $news, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments = $q->fetchAll();

        foreach ($comments as $comment){
            $comment->setDate(new \DateTime($comment->date()));
        }

        return $comments;
    }
}

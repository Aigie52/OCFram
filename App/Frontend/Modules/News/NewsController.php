<?php

namespace App\Frontend\Modules\News;


use Entity\Comment;
use FormBuilder\CommentFormBuilder;
use OCFram\BackController;
use OCFram\Form;
use OCFram\FormHandler;
use OCFram\HTTPRequest;
use OCFram\StringField;
use OCFram\TextField;

class NewsController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $nombreNews = $this->app->config()->get('nombre_news');
        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

        // On ajoute une définition pour le titre
        $this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');

        // On récupère le manager des news
        $manager = $this->managers->getManagerOf('News');

        $listeNews = $manager->getList(0, $nombreNews);

        foreach ($listeNews as $news) {
            if(strlen($news->contenu()) > $nombreCaracteres) {
                $debut = substr($news->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')). '...';

                $news->setContenu($debut);
            }
        }

        // On ajoute la variable $listeNews à la vue
        $this->page->addVar('listeNews', $listeNews);
    }

    public function executeShow(HTTPRequest $request)
    {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));

        if(empty($news)) {
            $this->app->httpResponse()->redirect404();
        }

        $this->page->addVar('title', $news->titre());
        $this->page->addVar('news', $news);
        $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id()));
    }

    public function executeInsertComment(HTTPRequest $request)
    {
        if($request->method() == 'POST') {
            $comment = new Comment([
                'news' => $request->getData('news'),
                'auteur' => $request->postData('pseudo'),
                'contenu' => $request->postData('contenu')
            ]);

        } else {
            $comment = new Comment();
        }

        $formBuilder = new CommentFormBuilder($comment);
        $formBuilder->build();

        $form = $formBuilder->form();
        $formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
        if($formHandler->process()) {
            $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
            $this->app->httpResponse()->redirect('news-'.$request->getData('news').'.html');
        }

        $this->page->addVar('comment', $comment);
        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Ajout d\'un commentaire');
    }
}

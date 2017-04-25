<?php

namespace OCFram;

class Page extends ApplicationComponent
{
    protected $contentFile;
    protected $vars = [];

    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var)) {
            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }

        $this->vars[$var] = $value;
    }

    public function getGeneratedPage($view = null)
    {
        // SI la vue existe déjà en cache, alors c'est celle-ci qu'on va afficher
        if (null !== $view) {
            ob_start();
            echo $view;
            return ob_get_clean();
        }

        if (!file_exists($this->contentFile)) {
            throw new \RuntimeException('La vue spécifiée n\'existe pas');
        }

        $user = $this->app->user();

        extract($this->vars);

        $frontIndex = '/^(frontend)[_][a-zA-Z]+[_](index)$/';
        // Si la page est bien la page du front index, alors on commence "l'enregistrement" des infos pour pouvoir les passer au cache
        if(preg_match($frontIndex, $this->app->viewCache()->filename())) {
            $this->app->viewCache()->start();
        } else {
            ob_start();
        }

        require $this->contentFile;
        $content = ob_get_clean();

        ob_start();
        require __DIR__ . '/../../App/' . $this->app->name() . '/Templates/layout.php';

        if(preg_match($frontIndex, $this->app->viewCache()->filename())) {
            return $this->app->viewCache()->end();
        }
        return ob_get_clean();
    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }
}
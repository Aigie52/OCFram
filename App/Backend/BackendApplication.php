<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 27/03/2017
 * Time: 13:09
 */

namespace App\Backend;


use OCFram\Application;

class BackendApplication extends Application
{

    public function __construct()
    {
        parent::__construct();
        $this->name = 'Backend';
    }

    public function run()
    {
        if($this->user->isAuthenticated()){
            $controller = $this->getController();
        } else {
            $controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index');
        }

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}

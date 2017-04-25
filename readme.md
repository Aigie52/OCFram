Une classe Cache a été créée dans lib\OCFram, deux classes en héritent :
- DataCache pour la mise en cache des données (comments/news)
- ViewCache pour la mise en cache de la vue index du frontend

Le DataCache est instancié dans la classe BackController pour pouvoir faire dans chaque contrôleur $this->cache

Le ViewCache est instancié dans la classe Application pour pouvoir appelé le fichier en cache avant que le NewsController soit appelé : dans la méthode getController, si la vue est déjà en cache, alors on appelle directement $this->httpResponse()->send($view);

La durée de vie de chacun des fichiers de cache est définie par défaut dans le fichier App\config.xml ou peut être défini lors de l'appel à la méthode createCache()
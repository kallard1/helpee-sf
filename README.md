#Pré-requis
- Composer
- NGINX
- PHP 7.3.5
- Postgres 11
- Redis
- Elasticsearch
- Docker
- Docker compose
- Node 10.16.0
- Yarn 1.6.0
- Un éditeur de texte


Le fichier `docker-compose.yml` contient toutes les configurations nécessaires pour le bon fonctionnement du projet.

Ne pas ouvrir le fichier `src/DataFixtures/CityFixtures.php` car il doit charger **~40000 lignes** de code et cela peut
prendre du temps voir faire planter votre éditeur de texte

---

##1 - Le dotenv
Pré-requis : 
- Un éditeur de texte
- **Copier** le fichier `.env.dist` et nommer la copie en `.env`<br>

Explication des variables d'environnement :
```.dotenv
APP_ENV : Défini sur l'environnement de « test ». Elle peut être défini avec « dev » et « prod » pour les environnements
de test et de production 

APP_SECRET : C'est une chaîne de caractère aléatoire et obligatoire cela permet de renforcer la sécurité
(tokens CSRF, salage des mots de passe...).
Il est recommandé de mettre au minimum 32 caractères.

DATABASE_URL : Ce sont les informations de connexion à la base de donnée

MAILER_URL : Ce sont les informations pour l'envoi d'emails

REDIS_HOST et REDIS_PORT : Sont les informations de connexion à la base de donnée REDIS
```

Il sera donc nécessaire de définir la variable `APP_SECRET`.
Si vous utilisez l'image docker, les informations de connexion à la base de données et redis sont
déjà renseignées pour sa configuration

##2 - Installation des dépendances de Symfony <br>
Pré-requis :
- Composer

Installation des dépendances avec la commande : `composer install -o`.
Le flag `-o` permet d'optimiser l'auto-loader.

Le temps d'installation des dépendances peut-être plus ou moins long selon la connexion internet

##3 - Docker<br>
Pré-requis :
- Docker
- Docker Compose

Lors de la première execution si les images ne sont pas disponible sur la machine, docker va les télécharger
automatiquement. Cela peut prendre plus ou moins de temps selon la connexion.

Pour lancer les containers : `docker-compose up -d`<br>
Pour arrêter les containers : `docker-compose stop`<br>
Pour supprimer les containers : `docker-compose down`

##4 - Yarn<br>
Pré-requis :
- Node
- NPM
- Yarn

Pour installer les dépendances node il suffit de faire `yarn install`

La compilation des assets se fait avec la commande `yarn build`

##5 - Peupler la base de données<br>
Pour y insérer les données de base (catégories, régions, départements, villes, ...) il faut executer les fixtures 
`docker exec -it php_helpee php bin/console doctrine:fixtures:load -n`

Le flag `-n` permet d'executer la commande de chargement des fixtures sans demander validation.

À utiliser **avec précautions** (et ne surtout pas l'utiliser en production) car cette commande vide le contenu des
tables avant de les remplir.

La commande peut prendre un certain temps car il y a énormément de données à importer (surtout pour les villes).



# BNB-Baribas

## Installation :

Démarrer la base de donnée :
`docker-compose up -d`
 
 Installer les dépendance php
 `composer install`

Créer & exécuter les migrations
`php bin/console make:migration`
`php bin/console doctrine:migrations:migrate`
  
  Lancer l'application Symfony
  `symfony serve`
  

## Notation :

  

- Application fonctionnelle (je peux lancer l'application et j'ai pas d'erreurs): **7pts**

- Code propre (suivre les psrs) : **3pts**

- Chaque fonctionnalitées au dessus : **1pts**

- Je n'arrive pas à casser l'application : **2pts**

- Un readme pour l'installation : **1pts**

 
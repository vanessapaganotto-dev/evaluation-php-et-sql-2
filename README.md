Pour la version anglaise, cliquez ici : [README_EN.md](README_EN.md)

# Application de Réservation d'Hôtel - PHP & SQL

## Description

Cette application simple permet de gérer la réservation de chambres d’hôtel.  
Elle est développée en PHP orienté objet et utilise une base de données MySQL pour stocker les informations des clients, hôtels, chambres et réservations.

L’application permet :  
- La gestion des clients (nom, email)  
- La gestion des hôtels (nom, adresse)  
- La gestion des chambres (numéro et hôtel associé)  
- La création et consultation des réservations avec dates de début et fin  

## Technologies utilisées

- PHP 8.x (POO)  
- MySQL / MariaDB  
- PDO pour la connexion sécurisée à la base de données  
- HTML/CSS pour le frontend simple  

## Installation

1. Cloner ce dépôt :  
 
   git clone https://github.com/ton-utilisateur/nom-du-projet.git

2. Importer la base de données :

Utiliser le fichier schema.sql fourni pour créer les tables et la structure.

Importer via phpMyAdmin ou en ligne de commande :

mysql -u utilisateur -p nom_base < schema.sql

3. Configurer la connexion à la base dans le fichier Database.php (host, dbname, user, password).

4. Déployer les fichiers sur un serveur local (ex: XAMPP, MAMP) ou distant supportant PHP.

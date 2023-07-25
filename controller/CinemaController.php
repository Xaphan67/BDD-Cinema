<?php

namespace Controller;
use Model\Connect;

class CinemaController
{
    // Liste les films
    public function listFilms()
    {
        // Connexion à la BDD
        $pdo = Connect::seConnecter();

        // Préparation d'une requête
        $requete = $pdo->query("
        SELECT *
        FROM film
        ");

        // Appel à la vue
        require "view/listFilms.php";
    }

    // Liste des acteurs
    public function listActeurs()
    {
        // Connexion à la BDD
        $pdo = Connect::seConnecter();

        // Préparation d'une requête
        $requete = $pdo->query("
        SELECT nom_personne, prenom_personne, sexe_personne, dateNaissance_personne
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        ");

        // Appel à la vue
        require "view/listActeurs.php";
    }
}
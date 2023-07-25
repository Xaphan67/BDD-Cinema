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
        $requete = $pdo->query("SELECT titre_film, anneeSortie_film  FROM film");

        // Appel à la vue
        require "view/listFilms.php";
    }
}
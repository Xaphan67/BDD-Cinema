<?php

namespace Controller;
use Model\Connect;

class CinemaController
{
    // Connexion à la BDD
    public function connectToBDD()
    {
        return Connect::seConnecter();
    }

    // Liste les films
    public function listFilms()
    {
        // Préparation d'une requête
        $requete = $this->connectToBDD()->query("
        SELECT *
        FROM film
        ");

        // Appel à la vue
        require "view/listFilms.php";
    }

    // Liste des réalisateurs
    public function listRealisateurs()
    {
        // Préparation d'une requête
        $requete = $this->connectToBDD()->query("
        SELECT nom_personne, prenom_personne, sexe_personne, dateNaissance_personne
        FROM realisateur r
        INNER JOIN personne p ON r.id_personne = p.id_personne
        ");

        // Appel à la vue
        require "view/listRealisateurs.php";
    }

    // Liste des acteurs
    public function listActeurs()
    {
        // Préparation d'une requête
        $requete = $this->connectToBDD()->query("
        SELECT nom_personne, prenom_personne, sexe_personne, dateNaissance_personne
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        ");

        // Appel à la vue
        require "view/listActeurs.php";
    }

    // Liste des genres
    public function listGenres()
    {
        // Préparation d'une requête
        $requete = $this->connectToBDD()->query("
        SELECT *
        FROM genre_film
        ");

        // Appel à la vue
        require "view/listGenres.php";
    }

    // Liste des rôles
    public function listRoles()
    {
        // Préparation d'une requête
        $requete = $this->connectToBDD()->query("
        SELECT *
        FROM rôle
        ");

        // Appel à la vue
        require "view/listRoles.php";
    }
}
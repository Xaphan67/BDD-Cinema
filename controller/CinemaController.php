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
        $films = $this->connectToBDD()->query("
        SELECT
        f.id_film AS IdFilm,
        f.titre_film,
        pe.id_personne,
        CONCAT(pe.nom_personne, ' ', pe.prenom_personne) AS realisateurFilm,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        GROUP_CONCAT(gf.libelle_genre_film SEPARATOR ', ') AS genres,
        f.affiche_film,
        f.synopsis_film, 
        (SELECT GROUP_CONCAT(CONCAT(p.nom_personne, ' ', p.prenom_personne) SEPARATOR ', ')
            FROM jouer j
            INNER JOIN acteur a ON j.id_acteur = a.id_acteur
            INNER JOIN personne p ON a.id_personne = p.id_personne
            INNER JOIN film f ON j.id_film = f.id_film
            WHERE f.id_film = IdFilm) AS acteursFilm,
        f.note_film
        FROM film f
        INNER JOIN realisateur r ON f.id_realisateur = r.id_realisateur
        INNER JOIN personne pe ON r.id_personne = pe.id_personne
        INNER JOIN posseder po ON f.id_film = po.id_film 
        INNER JOIN genre_film gf ON po.id_genre_film = gf.id_genre_film
        GROUP BY f.id_film
        ");

        // Appel à la vue
        require "view/listFilms.php";
    }

    public function listActeursFilm(int $id_film)
    {
        $acteurs = $this->connectToBDD()->query("
        SELECT
        GROUP_CONCAT(CONCAT(p.nom_personne, ' ', p.prenom_personne) SEPARATOR ', ')
        FROM jouer j
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN film f ON j.id_film = f.id_film
        WHERE f.id_film =
        " . $id_film);

        return $acteurs;
    }

    // Liste des réalisateurs
    public function listRealisateurs()
    {
        // Préparation d'une requête
        $requete = $this->connectToBDD()->query("
        SELECT
        r.id_realisateur,
        CONCAT(nom_personne, ' ', prenom_personne) AS realisateurFilm,
        sexe_personne,
        dateNaissance_personne
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
        SELECT 
        a.id_acteur,
        CONCAT(nom_personne, ' ', prenom_personne) AS acteurFilm,
        sexe_personne,
        dateNaissance_personne
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

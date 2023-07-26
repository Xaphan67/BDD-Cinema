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
        ORDER BY f.titre_film
        ");

        // Appel à la vue
        require "view/listFilms.php";
    }

    // Informations d'un film
    public function infosFilm(int $idFilm)
    {
        // Informations générales
        $film = $this->connectToBDD()->prepare("
        SELECT
        f.id_film,
        f.titre_film,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        GROUP_CONCAT(gf.libelle_genre_film SEPARATOR ', ') AS genres,
        affiche_film,
        f.note_film,
        f.id_realisateur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS realisateurFilm,
        f.synopsis_film
        FROM film f
        INNER JOIN realisateur r ON f.id_realisateur = r.id_realisateur
        INNER JOIN personne p ON p.id_personne = r.id_personne
        INNER JOIN posseder po ON f.id_film = po.id_film 
        INNER JOIN genre_film gf ON po.id_genre_film = gf.id_genre_film
        WHERE f.id_film = :idFilm");
        $film->execute((["idFilm" => $idFilm]));

        // Casting du film
        $acteurs = $this->connectToBDD()->prepare("
        SELECT
        a.id_acteur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS acteurFilm
        FROM jouer j
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN film f ON j.id_film = f.id_film
        WHERE f.id_film = :idFilm
        GROUP BY a.id_acteur"); // <-- GROUP BY pour éviter que si un acteur joue deux rôles dans le même film, il apparaisse deux fois
        $acteurs->execute((["idFilm" => $idFilm]));

        // Appel à la vue
        require "view/infosFilm.php";
    }

    // Liste des réalisateurs
    public function listRealisateurs()
    {
        // Préparation d'une requête
        $realisateurs = $this->connectToBDD()->query("
        SELECT
        r.id_realisateur,
        CONCAT(nom_personne, ' ', prenom_personne) AS realisateurFilm,
        sexe_personne,
        dateNaissance_personne
        FROM realisateur r
        INNER JOIN personne p ON r.id_personne = p.id_personne
        ORDER BY realisateurFilm
        ");

        // Appel à la vue
        require "view/listRealisateurs.php";
    }

    // Liste des acteurs
    public function listActeurs()
    {
        // Préparation d'une requête
        $acteurs = $this->connectToBDD()->query("
        SELECT 
        a.id_acteur,
        CONCAT(nom_personne, ' ', prenom_personne) AS acteurFilm,
        sexe_personne,
        dateNaissance_personne
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        ORDER BY acteurFilm
        ");

        // Appel à la vue
        require "view/listActeurs.php";
    }

    // Liste des genres
    public function listGenres()
    {
        // Préparation d'une requête
        $genres = $this->connectToBDD()->query("
        SELECT
        gf.id_genre_film,
        gf.libelle_genre_film,
        COUNT(*) AS nbFilms
        FROM film f
        INNER JOIN posseder p ON f.id_film = p.id_film
        INNER JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
        GROUP BY gf.id_genre_film
        ORDER BY gf.libelle_genre_film
        ");

        // Appel à la vue
        require "view/listGenres.php";
    }

    // Liste des rôles
    public function listRoles()
    {
        // Préparation d'une requête
        $roles = $this->connectToBDD()->query("
        SELECT
        j.id_rôle,
        r.nom_rôle,
        COUNT(*) AS nbActeurs
        FROM jouer j
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN rôle r ON j.id_rôle= r.id_rôle
        GROUP BY j.id_rôle
        ORDER BY r.nom_rôle
        ");

        // Appel à la vue
        require "view/listRoles.php";
    }
}

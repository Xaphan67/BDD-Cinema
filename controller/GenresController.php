<?php

namespace Controller;

use Model\Connect;

class GenresController
{
    // Connexion à la BDD
    public function connectToBDD()
    {
        return Connect::seConnecter();
    }

    // Liste des genres
    public function listGenres()
    {
        // Préparation d'une requête
        $genres = $this->connectToBDD()->query("
        SELECT
        gf.id_genre_film,
        gf.libelle_genre_film,
        COUNT(p.id_film) AS nbFilms
        FROM film f
        INNER JOIN posseder p ON f.id_film = p.id_film
        RIGHT JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
        GROUP BY gf.id_genre_film
        ORDER BY gf.libelle_genre_film
        ");

        // Appel à la vue
        require "view/listGenres.php";
    }

    // Informations d'un genre
    public function infosGenre($idGenre)
    {
        $BDD = $this->connectToBDD();

        $genre = $BDD->prepare("
        SELECT
        *
        FROM genre_film gf
        WHERE gf.id_genre_film = :idGenre
        ");
        $genre->execute(["idGenre" => $idGenre]);

        $films = $BDD->prepare(("
        SELECT
        f.id_film AS IdFilm,
        f.titre_film,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        f.id_realisateur,
        (SELECT CONCAT(p.nom_personne, ' ', p.prenom_personne)
				FROM realisateur r
				INNER JOIN personne p ON r.id_personne = p.id_personne
				INNER JOIN film f ON r.id_realisateur = f.id_realisateur
				WHERE f.id_film = IdFilm
                LIMIT 1) AS realisateurFilm,
        f.synopsis_film,
        f.note_film,
        f.affiche_film
        FROM jouer j
        INNER JOIN film f ON j.id_film = f.id_film
        INNER JOIN rôle r ON j.id_rôle = r.id_rôle
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN posseder po ON f.id_film = po.id_film 
        INNER JOIN genre_film gf ON po.id_genre_film = gf.id_genre_film
        WHERE po.id_genre_film = :idGenre
        GROUP BY f.id_film"));
        $films->execute(["idGenre" => $idGenre]);

        // Appel à la vue
        require "view/infosGenre.php";
    }

    // Formulaire d'ajout d'un genre
    public function formAddGenre()
    {
        // Appel à la vue
        require "view/formAddGenre.php";
    }

    // Ajout d'un genre
    public function addGenre()
    {
        if (isset($_POST['submit'])) {

            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom) {
                $genre = $this->connectToBDD()->prepare("
                INSERT INTO
                genre_film (libelle_genre_film)
                VALUES
                (:nomGenre)");
                $genre->execute(["nomGenre" => $_POST["nom"]]);
            }
        }

        header("Location:index.php?action=listGenres"); // Redirection vers la liste des genres
    }

    // Formulaire de modification d'un genre
    public function formEditGenre($idGenre)
    {
        if (isset($idGenre)) {

            $genre = $this->connectToBDD()->prepare("
            SELECT
            gf.id_genre_film,
            gf.libelle_genre_film
            FROM genre_film gf
            WHERE id_genre_film = :idGenre");
            $genre->execute(["idGenre" => $idGenre]);

            // Appel à la vue
            require "view/formEditGenre.php";
            exit();
        }

        header("Location:index.php?action=listGenres"); // Redirection vers la liste des genres si aucune id n'est spécifiée
    }

    // Modification d'un genre
    public function editGenre($idGenre)
    {
        if (isset($_POST['submit'])) {
            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom && isset($idGenre) && isset($_POST["nom"])) {
                $genre = $this->connectToBDD()->prepare("
                UPDATE genre_film
                SET libelle_genre_film = :nomGenre
                WHERE id_genre_film = :idGenre");
                $genre->execute(["nomGenre" => $_POST["nom"], "idGenre" => $idGenre]);
            }
        }

        header("Location:index.php?action=listGenres"); // Redirection vers la liste des genres
    }

    // Suppression d'un genre
    public function deleteGenre($idGenre)
    {
        if (isset($idGenre)) {
            $requete = $this->connectToBDD()->prepare("
            DELETE FROM genre_film
            WHERE id_genre_film = :idGenre");
            $requete->execute(["idGenre" => $idGenre]);
        }

        header("Location:index.php?action=listGenres"); // Redirection vers la liste des genres
    }
}

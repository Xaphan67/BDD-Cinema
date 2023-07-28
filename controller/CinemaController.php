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
        $film->execute(["idFilm" => $idFilm]);

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
        $acteurs->execute(["idFilm" => $idFilm]);

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

    // Informations d'un réalisateur
    public function infosRealisateur($idRealisateur)
    {
        $realisateur = $this->connectToBDD()->prepare("
        SELECT
        r.id_realisateur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS realisateurFilm,
        p.sexe_personne,
        p.dateNaissance_personne
        FROM personne p
        INNER JOIN realisateur r ON p.id_personne = r.id_personne
        WHERE r.id_realisateur = :idRealisateur");
        $realisateur->execute(["idRealisateur" => $idRealisateur]);

        $films = $this->connectToBDD()->prepare("
        SELECT
        f.id_film AS IdFilm,
        f.titre_film,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        GROUP_CONCAT(gf.libelle_genre_film SEPARATOR ', ') AS genres,
        f.synopsis_film,
        (SELECT GROUP_CONCAT(CONCAT(p.nom_personne, ' ', p.prenom_personne) SEPARATOR ', ')
            FROM jouer j
            INNER JOIN acteur a ON j.id_acteur = a.id_acteur
            INNER JOIN personne p ON a.id_personne = p.id_personne
            INNER JOIN film f ON j.id_film = f.id_film
            WHERE f.id_film = IdFilm) AS acteursFilm,
        f.note_film,
        f.affiche_film
        FROM film f
        INNER JOIN realisateur r ON f.id_realisateur = r.id_realisateur
        INNER JOIN personne p ON r.id_personne = p.id_personne
        INNER JOIN posseder po ON f.id_film = po.id_film 
        INNER JOIN genre_film gf ON po.id_genre_film = gf.id_genre_film
        WHERE r.id_realisateur = :idRealisateur
        GROUP BY f.id_film
        ORDER BY f.titre_film");
        $films->execute(["idRealisateur" => $idRealisateur]);

        // Appel à la vue
        require "view/infosRealisateur.php";
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

    // informations d'un acteur
    public function infosActeur($idActeur)
    {
        $acteur = $this->connectToBDD()->prepare("
        SELECT
        a.id_acteur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS acteurFilm,
        p.sexe_personne,
        p.dateNaissance_personne
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        WHERE a.id_acteur = :idActeur");
        $acteur->execute(["idActeur" => $idActeur]);

        $films = $this->connectToBDD()->prepare("
        SELECT
        f.id_film AS IdFilm,
        f.titre_film,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        GROUP_CONCAT(gf.libelle_genre_film SEPARATOR ', ') AS genres,
        f.id_realisateur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS realisateurFilm,
        f.synopsis_film,
        (SELECT GROUP_CONCAT(CONCAT(p.nom_personne, ' ', p.prenom_personne) SEPARATOR ', ')
            FROM jouer j
            INNER JOIN acteur a ON j.id_acteur = a.id_acteur
            INNER JOIN personne p ON a.id_personne = p.id_personne
            INNER JOIN film f ON j.id_film = f.id_film
            WHERE f.id_film = IdFilm) AS acteursFilm,
        f.note_film,
        f.affiche_film
        FROM jouer j
        INNER JOIN film f ON j.id_film = f.id_film
        INNER JOIN rôle r ON j.id_rôle = r.id_rôle
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN posseder po ON f.id_film = po.id_film 
        INNER JOIN genre_film gf ON po.id_genre_film = gf.id_genre_film
        WHERE a.id_acteur = :idActeur
        GROUP BY f.id_film");
        $films->execute(["idActeur" => $idActeur]);

        // Appel à la vue
        require "view/infosActeur.php";
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
        $genre = $this->connectToBDD()->prepare("
        SELECT
        *
        FROM genre_film gf
        WHERE gf.id_genre_film = :idGenre
        ");
        $genre->execute(["idGenre" => $idGenre]);

        $films = $this->connectToBDD()->prepare(("
        SELECT
        f.id_film AS IdFilm,
        f.titre_film,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        (SELECT GROUP_CONCAT(gf.libelle_genre_film SEPARATOR ', ')
				FROM posseder p
				INNER JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
				WHERE p.id_film = IdFilm) AS genres,
        f.id_realisateur,
        (SELECT CONCAT(p.nom_personne, ' ', p.prenom_personne)
				FROM realisateur r
				INNER JOIN personne p ON r.id_personne = p.id_personne
				INNER JOIN film f ON r.id_realisateur = f.id_realisateur
				WHERE f.id_realisateur = IdFilm) AS realisateurFilm,
        f.synopsis_film,
        (SELECT GROUP_CONCAT(CONCAT(p.nom_personne, ' ', p.prenom_personne) SEPARATOR ', ')
            FROM jouer j
            INNER JOIN acteur a ON j.id_acteur = a.id_acteur
            INNER JOIN personne p ON a.id_personne = p.id_personne
            INNER JOIN film f ON j.id_film = f.id_film
            WHERE f.id_film = IdFilm) AS acteursFilm,
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

    // Liste des rôles
    public function listRoles()
    {
        // Préparation d'une requête
        $roles = $this->connectToBDD()->query("
        SELECT
        r.id_rôle,
        r.nom_rôle,
        COUNT(j.id_acteur) AS nbActeurs
        FROM jouer j
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        RIGHT JOIN rôle r ON j.id_rôle= r.id_rôle
        GROUP BY r.id_rôle
        ORDER BY r.nom_rôle
        ");

        // Appel à la vue
        require "view/listRoles.php";
    }

    // Informations d'un rôle
    public function infosRole($idRole)
    {
        $role = $this->connectToBDD()->prepare("
        SELECT
        *
        FROM rôle r
        WHERE r.id_rôle = :idRole
        ");
        $role->execute(["idRole" => $idRole]);

        $acteurs = $this->connectToBDD()->prepare("
        SELECT
        a.id_acteur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS acteurFilm,
        p.sexe_personne,
        p.dateNaissance_personne
        FROM jouer j
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN rôle r ON j.id_rôle= r.id_rôle
        WHERE j.`id_rôle`= :idRole
        ORDER BY acteurFilm
        ");
        $acteurs->execute(["idRole" => $idRole]);

        // Appel à la vue
        require "view/infosRole.php";
    }

    // Formulaire d'ajout d'un rôle
    public function formAddRole()
    {
        // Appel à la vue
        require "view/formAddRole.php";
    }

    // Ajout d'un rôle
    public function addRole()
    {
        if (isset($_POST['submit'])) {

            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom) {
                $role = $this->connectToBDD()->prepare("
                INSERT INTO
                rôle (nom_rôle)
                VALUES
                (:nomRole)");
                $role->execute(["nomRole" => $_POST["nom"]]);
            }
        }

        header("Location:index.php?action=listRoles"); // Redirection vers la liste des rôles
    }

    // Formulaire de modification d'un rôle
    public function formEditRole($idRole)
    {
        if (isset($idRole)) {

            $role = $this->connectToBDD()->prepare("
            SELECT
            r.id_rôle,
            r.nom_rôle
            FROM rôle r
            WHERE id_rôle = :idRole");
            $role->execute(["idRole" => $idRole]);

            // Appel à la vue
            require "view/formEditRole.php";
            exit();
        }

        header("Location:index.php?action=listRoles"); // Redirection vers la liste des rôles si aucune id n'est spécifiée
    }

    // Modification d'un rôle
    public function editRole($idRole)
    {
        if (isset($_POST['submit'])) {
            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom && isset($idRole) && isset($_POST["nom"])) {
                $role = $this->connectToBDD()->prepare("
                UPDATE rôle
                SET nom_rôle = :nomRole
                WHERE id_rôle = :idRole");
                $role->execute(["nomRole" => $_POST["nom"], "idRole" => $idRole]);
            }
        }

        header("Location:index.php?action=listRoles"); // Redirection vers la liste des rôles
    }

    // Suppression d'un rôle
    public function deleteRole($idRole)
    {
        if (isset($idRole)) {
            $requete = $this->connectToBDD()->prepare("
            DELETE FROM rôle
            WHERE id_rôle = :idRole");
            $requete->execute(["idRole" => $idRole]);
        }

        header("Location:index.php?action=listRoles"); // Redirection vers la liste des rôles
    }
}

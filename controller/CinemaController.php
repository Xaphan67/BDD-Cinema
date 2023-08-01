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
        $BDD = $this->connectToBDD();

        $films = $BDD->query("
        SELECT
        f.id_film AS IdFilm,
        f.titre_film,
        f.id_realisateur,
        CONCAT(pe.nom_personne, ' ', pe.prenom_personne) AS realisateurFilm,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
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
        GROUP BY f.id_film
        ORDER BY f.titre_film
        ");

        $genres = $BDD->query("
        SELECT
        p.id_film,
        gf.id_genre_film,
        gf.libelle_genre_film
        FROM posseder p
        INNER JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
        ");

        // Appel à la vue
        require "view/listFilms.php";
    }

    // Informations d'un film
    public function infosFilm(int $idFilm)
    {
        $BDD = $this->connectToBDD();

        // Informations générales
        $film = $BDD->prepare("
        SELECT
        f.id_film,
        f.titre_film,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        affiche_film,
        f.note_film,
        f.id_realisateur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS realisateurFilm,
        f.synopsis_film
        FROM film f
        INNER JOIN realisateur r ON f.id_realisateur = r.id_realisateur
        INNER JOIN personne p ON p.id_personne = r.id_personne
        INNER JOIN posseder po ON f.id_film = po.id_film 
        WHERE f.id_film = :idFilm");
        $film->execute(["idFilm" => $idFilm]);

        $genres = $BDD->prepare("
        SELECT
        p.id_genre_film, gf.libelle_genre_film
        FROM posseder p
        INNER JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
        WHERE p.id_film = :idFilm");
        $genres->execute(["idFilm" => $idFilm]);

        // Casting du film
        $acteurs = $BDD->prepare("
        SELECT
        a.id_acteur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS acteurFilm,
        r.id_rôle,
        r.nom_rôle
        FROM jouer j
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN film f ON j.id_film = f.id_film
        INNER JOIN rôle r ON j.id_rôle = r.id_rôle
        WHERE f.id_film = :idFilm
        ORDER BY acteurFilm");
        $acteurs->execute(["idFilm" => $idFilm]);

        // Appel à la vue
        require "view/infosFilm.php";
    }
    
    // Formulaire d'ajout d'un film
    public function formAddFilm()
    {
        $BDD = $this->connectToBDD();

        $realisateurs = $BDD->query("
        SELECT
        r.id_realisateur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS realisateurFilm
        FROM realisateur r
        INNER JOIN personne p ON r.id_personne = p.id_personne
        ORDER BY realisateurFilm
        ");

        $genres = $BDD->query("
        SELECT
        *
        FROM genre_film
        ORDER BY libelle_genre_film
        ");

        // Appel à la vue
        require "view/formAddFilm.php";
    }

    // Ajout d'un film
    public function addFilm()
    {
        if (isset($_POST['submit'])) {

            // Sécurité
            $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_STRING);
            $annee = filter_input(INPUT_POST, "annee", FILTER_VALIDATE_INT);
            $duree = filter_input(INPUT_POST, "duree", FILTER_VALIDATE_INT);
            $synopsis = filter_input(INPUT_POST, "synopsis", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $note = filter_input(INPUT_POST, "note", FILTER_VALIDATE_INT);

            // Stocke les information du fichier envoyé
            $affiche = "";
            if (isset($_FILES['affiche'])) {
                $tmpName = $_FILES['affiche']['tmp_name'];
                $filename = $_FILES['affiche']['name'];
                $size = $_FILES['affiche']['size'];
                $error = $_FILES['affiche']['error'];

                $tabExtension = explode('.', $filename); // Sépare le nom du fichier et son extension
                $extension = strtolower(end($tabExtension)); // Stock l'extension

                //Tableau des extensions acceptées
                $extensions = ['jpg', 'png', 'jpeg', 'gif'];

                // Taille maximale acceptée (en bytes)
                $maxSize = 40000000;

                // Vérifie que l'extension et la taille sont accepté
                if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                    $uniqueName = uniqid('', true);
                    $affiche = $uniqueName . "." . $extension;
                    move_uploaded_file($tmpName, './public/img/posters/' . $affiche); // Upload le fichier dans le dossier des affiches
                } else { // Erreur : Renvoie au formulaire

                    header("Location:index.php?action=formAddFilm");
                    exit();
                }
            }

            if ($titre && $annee && $duree && $note && $affiche) {
                $BDD = $this->connectToBDD();

                $film = $BDD->prepare("
                INSERT INTO
                film (titre_film, anneeSortie_film, duree_film, synopsis_film, note_film, affiche_film, id_realisateur)
                VALUES
                (:titreFilm, :anneeFilm, :dureeFilm, :synopsisFilm, :noteFilm, :afficheFilm, :idRealisateurFilm)");
                $film->execute(["titreFilm" => $titre, "anneeFilm" => $annee, "dureeFilm" => $duree, "synopsisFilm" => $synopsis, "noteFilm" => $note, "afficheFilm" => $affiche, "idRealisateurFilm" => $_POST["realisateur"]]);

                // Récupère l'id du film ajouté
                $filmID = $BDD->prepare("
                SELECT
                f.id_film
                FROM film f
                WHERE f.titre_film = :titreFilm");
                $filmID->execute(["titreFilm" => $_POST["titre"]]);
                $filmID = $filmID->fetch();

                // Ajoute chaque genre au film
                foreach ($_POST["genres"] as $genre) {
                    $requete = $BDD->prepare("
                    INSERT INTO
                    posseder (id_film, id_genre_film)
                    VALUES
                    (:idFilm, :idGenre)");
                    $requete->execute(["idFilm" => $filmID["id_film"], "idGenre" => $genre]);
                }
            }
        }

        header("Location:index.php?action=formAddCasting&id=" . $filmID["id_film"]); // Redirection vers ajout d'un acteur au film
    }

    // Formulaire de modification d'un film
    public function formEditFilm($idFilm)
    {
        if (isset($idFilm)) {
            $BDD = $this->connectToBDD();

            $film = $BDD->prepare("
            SELECT
            f.id_film, f.titre_film, f.anneeSortie_film, f.duree_film, f.synopsis_film, f.note_film, f.affiche_film, f.id_realisateur
            FROM film f
            WHERE f.id_film = :idFilm");
            $film->execute(["idFilm" => $idFilm]);

            $realisateurs = $BDD->query("
            SELECT
            r.id_realisateur,
            CONCAT(p.nom_personne, ' ', p.prenom_personne) AS realisateurFilm
            FROM realisateur r
            INNER JOIN personne p ON r.id_personne = p.id_personne
            ORDER BY realisateurFilm
            ");

            $genres = $BDD->query("
            SELECT
            *
            FROM genre_film
            ORDER BY libelle_genre_film
            ");

            $genresSelected = $BDD->prepare("
            SELECT
            p.id_genre_film
            FROm posseder p
            WHERE p.id_film = :idFilm");
            $genresSelected->execute(["idFilm" => $idFilm]);

            // Appel à la vue
            require "view/formEditFilm.php";
        }
    }

    // Modification d'un film
    public function editFilm($idFilm)
    {
        // Sécurité
        $titre = filter_input(INPUT_POST, "titre", FILTER_SANITIZE_STRING);
        $annee = filter_input(INPUT_POST, "annee", FILTER_VALIDATE_INT);
        $duree = filter_input(INPUT_POST, "duree", FILTER_VALIDATE_INT);
        $synopsis = filter_input(INPUT_POST, "synopsis", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $note = filter_input(INPUT_POST, "note", FILTER_VALIDATE_INT);

        // Stocke les information du fichier envoyé
        $affiche = "";
        if (isset($_FILES['affiche']) && $_FILES['affiche']["name"] != "") {
            $tmpName = $_FILES['affiche']['tmp_name'];
            $filename = $_FILES['affiche']['name'];
            $size = $_FILES['affiche']['size'];
            $error = $_FILES['affiche']['error'];

            $tabExtension = explode('.', $filename); // Sépare le nom du fichier et son extension
            $extension = strtolower(end($tabExtension)); // Stock l'extension

            //Tableau des extensions acceptées
            $extensions = ['jpg', 'png', 'jpeg', 'gif'];

            // Taille maximale acceptée (en bytes)
            $maxSize = 40000000;

            // Vérifie que l'extension et la taille sont accepté
            if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                $uniqueName = uniqid('', true);
                $affiche = $uniqueName . "." . $extension;

                // Supprime l'affiche actuelle du film
                $afficheActuelle = $this->connectToBDD()->prepare("
                SELECT
                f.affiche_film
                FROM film f
                WHERE id_film = :idFilm");
                $afficheActuelle->execute(["idFilm" => $idFilm]);
                $afficheActuelle = $afficheActuelle->fetch();

                unlink("./public/img/posters/" . $afficheActuelle["affiche_film"]);

                move_uploaded_file($tmpName, './public/img/posters/' . $affiche); // Upload le fichier dans le dossier des affiches
            } else { // Erreur : Renvoie au formulaire

                header("Location:index.php?action=formAddFilm");
                exit();
            }
        }

        if ($titre && $annee && $duree && $note) {

            $BDD = $this->connectToBDD();

            if ($affiche)
            {
                $film = $BDD->prepare("
                UPDATE film
                SET titre_film = :titreFilm, anneeSortie_film = :anneeFilm, duree_film = :dureeFilm, synopsis_film = :synopsisFilm, note_film = :noteFilm, affiche_film = :afficheFilm, id_realisateur = :idRealisateurFilm
                WHERE id_film = :idFilm");
                $film->execute(["idFilm" => $idFilm, "titreFilm" => $titre, "anneeFilm" => $annee, "dureeFilm" => $duree, "synopsisFilm" => $synopsis, "noteFilm" => $note, "afficheFilm" => $affiche, "idRealisateurFilm" => $_POST["realisateur"]]);
            }
           else
           {
                $film = $BDD->prepare("
                UPDATE film
                SET titre_film = :titreFilm, anneeSortie_film = :anneeFilm, duree_film = :dureeFilm, synopsis_film = :synopsisFilm, note_film = :noteFilm, id_realisateur = :idRealisateurFilm
                WHERE id_film = :idFilm");
                $film->execute(["idFilm" => $idFilm, "titreFilm" => $titre, "anneeFilm" => $annee, "dureeFilm" => $duree, "synopsisFilm" => $synopsis, "noteFilm" => $note, "idRealisateurFilm" => $_POST["realisateur"]]);
           }

            // Supprime les genres associés au film
            $genres = $BDD->prepare("
             DELETE FROM posseder
             WHERE id_film = :idFilm");
            $genres->execute(["idFilm" => $idFilm]);

            // Ajoute chaque nouveau genre au film
            foreach ($_POST["genres"] as $genre) {
                $requete = $BDD->prepare("
                 INSERT INTO
                 posseder (id_film, id_genre_film)
                 VALUES
                 (:idFilm, :idGenre)");
                $requete->execute(["idFilm" => $idFilm, "idGenre" => $genre]);
            }
        }

        header("Location:index.php?action=listFilms"); // Redirection vers la liste des films
    }

    // Suppression d'un film
    public function deleteFilm($idFilm)
    {
        if (isset($idFilm)) {
            $BDD = $this->connectToBDD();

            // Récupère le nom de l'affiche du film
            $affiche = $BDD->prepare("
            SELECT
            affiche_film
            FROM film
            WHERE id_film = :idFilm");
            $affiche->execute(["idFilm" => $idFilm]);
            $affiche = $affiche->fetch();

            // Supprime l'affiche du film
            unlink("./public/img/posters/" . $affiche["affiche_film"]);

            // Supprime l'association aux genres
            $genres = $BDD->prepare("
            DELETE FROM posseder
            WHERE id_Film = :idFilm");
            $genres->execute(["idFilm" => $idFilm]);

            // Supprime l'association aux rôles
            $castings = $BDD->prepare("
            DELETE FROM jouer
            WHERE id_Film = :idFilm");
            $castings->execute(["idFilm" => $idFilm]);

            // Supprime le film
            $film = $BDD->prepare("
            DELETE FROM film
            WHERE id_film = :idFilm");
            $film->execute(["idFilm" => $idFilm]);
        }

        header("Location:index.php?action=listFilms"); // Redirection vers la liste des films
    }

    // Formulaire d'ajout d'un acteur à un film
    public function formAddCasting($idFilm)
    {
        $BDD = $this->connectToBDD();

        $acteurs = $BDD->query("
        SELECT
        a.id_acteur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS acteurFilm
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        ORDER BY acteurFilm
        ");

        $roles = $BDD->query("
        SELECT
        *
        FROM rôle
        ORDER BY nom_rôle
        ");

        // Appel à la vue
        require "view/formAddCasting.php";
    }

    // Ajout d'un acteur à un film
    public function addCasting($idFilm)
    {
        if (isset($_POST['submit'])) {

            if (isset($_POST["acteur"]) && isset($_POST["role"])) {
                // Ajoute les infos de l'acteur dans la table jouer
                $acteur = $this->connectToBDD()->prepare("
                 INSERT INTO
                 jouer (id_film, id_acteur, id_rôle)
                 VALUES
                 (:idFilm, :idActeur, :idRole)");
                $acteur->execute(["idFilm" => $idFilm, "idActeur" => $_POST["acteur"], "idRole" => $_POST["role"]]);
            }
        }

        header("Location:index.php?action=infoFilm&id=$idFilm"); // Redirection vers les informations du film correspondant
    }

    // Suppression d'un acteur d'un film
    public function deleteCasting($idFilm, $idActeur, $idRole)
    {
        if (isset($idFilm) && isset($idActeur) && isset($idRole)) {
            $requete = $this->connectToBDD()->prepare("
            DELETE FROM jouer
            WHERE id_film = :idFilm AND id_acteur = :idActeur AND id_rôle = :idRole");
            $requete->execute(["idFilm" => $idFilm, "idActeur" => $idActeur, "idRole" => $idRole]);
        }

        header("Location:index.php?action=infoFilm&id=$idFilm"); // Redirection vers les informations du film correspondant
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
        $BDD = $this->connectToBDD();

        $realisateur = $BDD->prepare("
        SELECT
        r.id_realisateur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS realisateurFilm,
        p.sexe_personne,
        p.dateNaissance_personne
        FROM personne p
        INNER JOIN realisateur r ON p.id_personne = r.id_personne
        WHERE r.id_realisateur = :idRealisateur");
        $realisateur->execute(["idRealisateur" => $idRealisateur]);

        $films = $BDD->prepare("
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

        $genres = $BDD->query("
        SELECT
        p.id_film,
        gf.id_genre_film,
        gf.libelle_genre_film
        FROM posseder p
        INNER JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
        ");

        // Appel à la vue
        require "view/infosRealisateur.php";
    }

    // Formulaire d'ajout d'un réalisateur
    public function formAddRealisateur()
    {
        // Appel à la vue
        require "view/formAddRealisateur.php";
    }

    // Ajout d'un réalisateur
    public function addRealisateur()
    {
        if (isset($_POST['submit'])) {

            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom && $prenom && isset($_POST["sexe"]) && isset($_POST["dateNaissance"])) {
                $BDD = $this->connectToBDD();

                // Ajoute les infos du réalisateur dans la talbe personne
                $personne = $BDD->prepare("
                INSERT INTO
                personne (nom_personne, prenom_personne, sexe_personne, dateNaissance_personne)
                VALUES
                (:nomPersonne, :prenomPersonne, :sexePersonne, :dateNaissancePersonne)");
                $personne->execute(["nomPersonne" => $_POST["nom"], "prenomPersonne" => $_POST["prenom"], "sexePersonne" => $_POST["sexe"], "dateNaissancePersonne" => $_POST["dateNaissance"]]);

                // Récupère l'id de la personne ajoutée
                $personneID = $BDD->prepare("
                SELECT
                p.id_personne
                FROM personne p
                WHERE p.nom_personne = :nomPersonne AND p.prenom_personne = :prenomPersonne");
                $personneID->execute(["nomPersonne" => $_POST["nom"], "prenomPersonne" => $_POST["prenom"]]);
                $personneID = $personneID->fetch();

                // Ajoute l'id de la personne dans la table réalisateur
                $realisateur = $BDD->prepare("
                INSERT INTO
                realisateur (id_personne)
                VALUES
                (:idPersonne)");
                $realisateur->execute(["idPersonne" => $personneID["id_personne"]]);
            }
        }

        header("Location:index.php?action=listRealisateurs"); // Redirection vers la liste des réalisateurs
    }

    // Formulaire de modification d'un réalisateur
    public function formEditRealisateur($idRealisateur)
    {
        if (isset($idRealisateur)) {

            $realisateur = $this->connectToBDD()->prepare("
            SELECT
            r.id_realisateur,
            p.id_personne,
            p.nom_personne,
            p.prenom_personne,
            p.sexe_personne,
            p.dateNaissance_personne
            FROM realisateur r
            INNER JOIN personne p ON r.id_personne = p.id_personne
            WHERE r.id_realisateur = :idRealisateur");
            $realisateur->execute(["idRealisateur" => $idRealisateur]);

            // Appel à la vue
            require "view/formEditRealisateur.php";
            exit();
        }

        header("Location:index.php?action=listRealisateurs"); // Redirection vers la liste des réalisateurs si aucune id n'est spécifiée
    }

    // Modification d'un réalisateur
    public function editRealisateur($idRealisateur)
    {
        if (isset($_POST['submit'])) {
            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom && $prenom && isset($idRealisateur) && isset($_POST["sexe"]) && isset($_POST["dateNaissance"])) {
                $BDD = $this->connectToBDD();

                // Récupère l'id de la personne correspondant à $idRealisateur
                $personneID = $BDD->prepare("
                SELECT
                r.id_personne
                FROM realisateur r
                WHERE r.id_realisateur = :idRealisateur");
                $personneID->execute(["idRealisateur" => $idRealisateur]);
                $personneID = $personneID->fetch();

                // Modifie la personne correspondante
                $personne = $BDD->prepare("
                UPDATE personne
                SET
                nom_personne = :nomPersonne,
                prenom_personne = :prenomPersonne,
                sexe_personne = :sexePersonne,
                dateNaissance_personne = :dateNaissancePersonne
                WHERE id_personne = :idPersonne");
                $personne->execute(["nomPersonne" => $_POST["nom"], "prenomPersonne" => $_POST["prenom"], "sexePersonne" => $_POST["sexe"], "dateNaissancePersonne" => $_POST["dateNaissance"], "idPersonne" => $personneID["id_personne"]]);
            }
        }

        header("Location:index.php?action=listRealisateurs"); // Redirection vers la liste des réalisateurs
    }

    // Suppression d'un réalisateur
    public function deleteRealisateur($idRealisateur)
    {
        if (isset($idRealisateur)) {
            $BDD = $this->connectToBDD();

            // Récupère l'id de la personne correspondant à $idRealisateur
            $personneID = $BDD->prepare("
            SELECT
            r.id_personne
            FROM realisateur r
            WHERE r.id_realisateur = :idRealisateur");
            $personneID->execute(["idRealisateur" => $idRealisateur]);
            $personneID = $personneID->fetch();

            // Supprime la personne correspondant au réalisateur
            $personne = $BDD->prepare("
            DELETE FROM personne
            WHERE id_personne = :idPersonne");
            $personne->execute(["idPersonne" => $personneID["id_personne"]]);
        }

        header("Location:index.php?action=listRealisateurs"); // Redirection vers la liste des réalisateurs
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

    // Informations d'un acteur
    public function infosActeur($idActeur)
    {
        $BDD = $this->connectToBDD();

        $acteur = $BDD->prepare("
        SELECT
        a.id_acteur,
        CONCAT(p.nom_personne, ' ', p.prenom_personne) AS acteurFilm,
        p.sexe_personne,
        p.dateNaissance_personne
        FROM acteur a
        INNER JOIN personne p ON a.id_personne = p.id_personne
        WHERE a.id_acteur = :idActeur");
        $acteur->execute(["idActeur" => $idActeur]);

        $films = $BDD->prepare("
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

        $genres = $BDD->query("
        SELECT
        p.id_film,
        gf.id_genre_film,
        gf.libelle_genre_film
        FROM posseder p
        INNER JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
        ");

        // Appel à la vue
        require "view/infosActeur.php";
    }

    // Formulaire d'ajout d'un acteur
    public function formAddActeur()
    {
        $roles = $this->connectToBDD()->query("
        SELECT
        *
        FROM rôle
        ");

        // Appel à la vue
        require "view/formAddActeur.php";
    }

    // Ajout d'un acteur
    public function addActeur()
    {
        if (isset($_POST['submit'])) {

            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom && $prenom && isset($_POST["sexe"]) && isset($_POST["dateNaissance"])) {
                $BDD = $this->connectToBDD();

                // Ajoute les infos de l'acteur dans la talbe personne
                $personne = $BDD->prepare("
                 INSERT INTO
                 personne (nom_personne, prenom_personne, sexe_personne, dateNaissance_personne)
                 VALUES
                 (:nomPersonne, :prenomPersonne, :sexePersonne, :dateNaissancePersonne)");
                $personne->execute(["nomPersonne" => $_POST["nom"], "prenomPersonne" => $_POST["prenom"], "sexePersonne" => $_POST["sexe"], "dateNaissancePersonne" => $_POST["dateNaissance"]]);

                // Récupère l'id de la personne ajoutée
                $personneID = $BDD->prepare("
                 SELECT
                 p.id_personne
                 FROM personne p
                 WHERE p.nom_personne = :nomPersonne AND p.prenom_personne = :prenomPersonne");
                $personneID->execute(["nomPersonne" => $_POST["nom"], "prenomPersonne" => $_POST["prenom"]]);
                $personneID = $personneID->fetch();

                // Ajoute l'id de la personne dans la table acteur
                $acteur = $BDD->prepare("
                 INSERT INTO
                 acteur (id_personne)
                 VALUES
                 (:idPersonne)");
                $acteur->execute(["idPersonne" => $personneID["id_personne"]]);
            }
        }

        header("Location:index.php?action=listActeurs"); // Redirection vers la liste des acteurs
    }

    // Formulaire de modification d'un acteur
    public function formEditActeur($idActeur)
    {
        if (isset($idActeur)) {

            $acteur = $this->connectToBDD()->prepare("
             SELECT
             a.id_acteur,
             p.id_personne,
             p.nom_personne,
             p.prenom_personne,
             p.sexe_personne,
             p.dateNaissance_personne
             FROM acteur a
             INNER JOIN personne p ON a.id_personne = p.id_personne
             WHERE a.id_acteur = :idActeur");
            $acteur->execute(["idActeur" => $idActeur]);

            // Appel à la vue
            require "view/formEditActeur.php";
            exit();
        }

        header("Location:index.php?action=listActeurs"); // Redirection vers la liste des acteurs si aucune id n'est spécifiée
    }

    // Modification d'un acteur
    public function editActeur($idActeur)
    {
        if (isset($_POST['submit'])) {
            // Sécurité
            $nom = filter_input(INPUT_POST, "nom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $prenom = filter_input(INPUT_POST, "prenom", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nom && $prenom && isset($idActeur) && isset($_POST["sexe"]) && isset($_POST["dateNaissance"])) {
                $BDD = $this->connectToBDD();

                // Récupère l'id de la personne correspondant à $idActeur
                $personneID = $BDD->prepare("
                 SELECT
                 a.id_personne
                 FROM acteur a
                 WHERE a.id_acteur = :idActeur");
                $personneID->execute(["idActeur" => $idActeur]);
                $personneID = $personneID->fetch();

                // Modifie la personne correspondante
                $personne = $BDD->prepare("
                 UPDATE personne
                 SET
                 nom_personne = :nomPersonne,
                 prenom_personne = :prenomPersonne,
                 sexe_personne = :sexePersonne,
                 dateNaissance_personne = :dateNaissancePersonne
                 WHERE id_personne = :idPersonne");
                $personne->execute(["nomPersonne" => $_POST["nom"], "prenomPersonne" => $_POST["prenom"], "sexePersonne" => $_POST["sexe"], "dateNaissancePersonne" => $_POST["dateNaissance"], "idPersonne" => $personneID["id_personne"]]);
            }
        }

        header("Location:index.php?action=listActeurs"); // Redirection vers la liste des acteurs
    }

    // Suppression d'un acteur
    public function deleteActeur($idActeur)
    {
        if (isset($idActeur)) {
            $BDD = $this->connectToBDD();

            // Récupère l'id de la personne correspondant à $idActeur
            $personneID = $BDD->prepare("
                 SELECT
                 a.id_personne
                 FROM acteur a
                 WHERE a.id_acteur = :idActeur");
            $personneID->execute(["idActeur" => $idActeur]);
            $personneID = $personneID->fetch();

            // Supprime la personne correspondant à l'acteur
            $personne = $BDD->prepare("
                 DELETE FROM personne
                 WHERE id_personne = :idPersonne");
            $personne->execute(["idPersonne" => $personneID["id_personne"]]);
        }

        header("Location:index.php?action=listActeurs"); // Redirection vers la liste des acteurs
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
        (SELECT GROUP_CONCAT(gf.libelle_genre_film SEPARATOR ', ')
				FROM posseder p
				INNER JOIN genre_film gf ON p.id_genre_film = gf.id_genre_film
				WHERE p.id_film = IdFilm) AS genres,
        f.id_realisateur,
        (SELECT CONCAT(p.nom_personne, ' ', p.prenom_personne)
				FROM realisateur r
				INNER JOIN personne p ON r.id_personne = p.id_personne
				INNER JOIN film f ON r.id_realisateur = f.id_realisateur
				WHERE f.id_film = IdFilm
                LIMIT 1) AS realisateurFilm,
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
        $BDD = $this->connectToBDD();

        $role = $BDD->prepare("
        SELECT
        *
        FROM rôle r
        WHERE r.id_rôle = :idRole
        ");
        $role->execute(["idRole" => $idRole]);

        $acteurs = $BDD->prepare("
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

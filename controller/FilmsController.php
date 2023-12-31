<?php

namespace Controller;

use Model\Connect;

class FilmsController
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
        f.note_film
        FROM film f
        INNER JOIN realisateur r ON f.id_realisateur = r.id_realisateur
        INNER JOIN personne pe ON r.id_personne = pe.id_personne
        INNER JOIN posseder po ON f.id_film = po.id_film 
        GROUP BY f.id_film
        ORDER BY f.anneeSortie_film DESC
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
                $extensions = ['jpg', 'png', 'jpeg'];

                // Taille maximale acceptée (en bytes)
                $maxSize = 40000000;

                // Vérifie que l'extension et la taille sont accepté
                if (in_array($extension, $extensions) && $size <= $maxSize && $error == 0) {
                    $uniqueName = uniqid('', true);
                    $affiche = $uniqueName . "." . $extension;
                    move_uploaded_file($tmpName, './public/img/posters/' . $affiche); // Upload le fichier dans le dossier des affiches
                } else { // Erreur : Renvoie au formulaire

                    if (!in_array($extension, $extensions)) {
                        $_SESSION['message'][0] = "ExtError";
                    } else if ($size >= $maxSize) {
                        $_SESSION['message'][0] = "SizeError";
                    } else if ($error != 0) {
                        $_SESSION['message'][0] = "FileError";
                    }
                    header("Location:index.php?action=formAddFilm");
                    exit();
                }
            }

            if ($titre && $annee && $duree && $note && $affiche) { // Pas d'erreur, ajoute le film
                $BDD = $this->connectToBDD();

                $film = $BDD->prepare("
                INSERT INTO
                film (titre_film, anneeSortie_film, duree_film, synopsis_film, note_film, affiche_film, id_realisateur)
                VALUES
                (:titreFilm, :anneeFilm, :dureeFilm, :synopsisFilm, :noteFilm, :afficheFilm, :idRealisateurFilm)");
                $film->execute(["titreFilm" => $titre, "anneeFilm" => $annee, "dureeFilm" => $duree, "synopsisFilm" => $synopsis, "noteFilm" => $note, "afficheFilm" => $affiche, "idRealisateurFilm" => $_POST["realisateur"]]);

                // Récupère l'id du film ajouté
                $filmID = intval($BDD->lastInsertId());

                // Ajoute chaque genre au film
                foreach ($_POST["genres"] as $genre) {
                    $requete = $BDD->prepare("
                    INSERT INTO
                    posseder (id_film, id_genre_film)
                    VALUES
                    (:idFilm, :idGenre)");
                    $requete->execute(["idFilm" => $filmID, "idGenre" => $genre]);
                }
            } else { // Erreur : Renvoie au formulaire
                $_SESSION['message'][0] = "Error";
                header("Location:index.php?action=formAddFilm");
                exit();
            }
        }

        header("Location:index.php?action=formAddCasting&id=" . $filmID); // Redirection vers ajout d'un acteur au film
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
            $extensions = ['jpg', 'png', 'jpeg'];

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

                if (!in_array($extension, $extensions)) {
                    $_SESSION['message'][0] = "ExtError";
                } else if ($size >= $maxSize) {
                    $_SESSION['message'][0] = "SizeError";
                } else if ($error != 0) {
                    $_SESSION['message'][0] = "FileError";
                }
                header("Location:index.php?action=formEditFilm&id=" . $idFilm);
                exit();
            }
        }

        if ($titre && $annee && $duree && $note) { // Pas d'erreur, modifie le film

            $BDD = $this->connectToBDD();

            if ($affiche) {
                $film = $BDD->prepare("
                UPDATE film
                SET titre_film = :titreFilm, anneeSortie_film = :anneeFilm, duree_film = :dureeFilm, synopsis_film = :synopsisFilm, note_film = :noteFilm, affiche_film = :afficheFilm, id_realisateur = :idRealisateurFilm
                WHERE id_film = :idFilm");
                $film->execute(["idFilm" => $idFilm, "titreFilm" => $titre, "anneeFilm" => $annee, "dureeFilm" => $duree, "synopsisFilm" => $synopsis, "noteFilm" => $note, "afficheFilm" => $affiche, "idRealisateurFilm" => $_POST["realisateur"]]);
            } else {
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
        } else { // Erreur : Renvoie au formulaire
            $_SESSION['message'][0] = "Error";
            header("Location:index.php?action=formEditFilm&id=" . $idFilm);
            exit();
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
}

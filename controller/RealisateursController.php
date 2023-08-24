<?php

namespace Controller;

use Model\Connect;

class RealisateursController
{
    // Connexion à la BDD
    public function connectToBDD()
    {
        return Connect::seConnecter();
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
        dateNaissance_personne,
        COUNT(f.id_film) AS nbFilms
        FROM realisateur r
        INNER JOIN personne p ON r.id_personne = p.id_personne
        LEFT JOIN film f ON r.id_realisateur = f.id_realisateur
        GROUP BY r.id_realisateur
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
        f.affiche_film
        FROM film f
        INNER JOIN realisateur r ON f.id_realisateur = r.id_realisateur
        INNER JOIN personne p ON r.id_personne = p.id_personne
        WHERE r.id_realisateur = :idRealisateur
        GROUP BY f.id_film
        ORDER BY f.titre_film");
        $films->execute(["idRealisateur" => $idRealisateur]);

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
                $personneID = intval($BDD->lastInsertId());

                // Ajoute l'id de la personne dans la table réalisateur
                $realisateur = $BDD->prepare("
                INSERT INTO
                realisateur (id_personne)
                VALUES
                (:idPersonne)");
                $realisateur->execute(["idPersonne" => $personneID]);
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
}

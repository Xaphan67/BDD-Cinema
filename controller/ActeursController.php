<?php

namespace Controller;

use Model\Connect;

class ActeursController
{
    // Connexion à la BDD
    public function connectToBDD()
    {
        return Connect::seConnecter();
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
        dateNaissance_personne,
        COUNT(j.id_film) AS nbFilms
        FROM acteur a
        INNER JOIN personne p ON p.id_personne = a.id_personne
        LEFT JOIN jouer j ON j.id_acteur = a.id_acteur
        GROUP BY a.id_acteur
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
        f.id_film,
        f.titre_film,
        f.anneeSortie_film,
        TIME_FORMAT(SEC_TO_TIME(f.duree_film * 60), '%Hh %imin') AS duree,
        f.affiche_film,
        j.id_rôle,
        r.nom_rôle
        FROM jouer j
        INNER JOIN film f ON j.id_film = f.id_film
        INNER JOIN rôle r ON j.id_rôle = r.id_rôle
        WHERE j.id_acteur = :idActeur
        ORDER BY f.anneeSortie_film DESC");
        $films->execute(["idActeur" => $idActeur]);

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
                $personneID = intval($BDD->lastInsertId());

                // Ajoute l'id de la personne dans la table acteur
                $acteur = $BDD->prepare("
                 INSERT INTO
                 acteur (id_personne)
                 VALUES
                 (:idPersonne)");
                $acteur->execute(["idPersonne" => $personneID]);
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
}

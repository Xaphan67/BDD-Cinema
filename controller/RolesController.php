<?php

namespace Controller;

use Model\Connect;

class RolesController
{
    // Connexion à la BDD
    public function connectToBDD()
    {
        return Connect::seConnecter();
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
        p.dateNaissance_personne,
        f.id_film,
        f.titre_film
        FROM jouer j
        INNER JOIN acteur a ON j.id_acteur = a.id_acteur
        INNER JOIN personne p ON a.id_personne = p.id_personne
        INNER JOIN rôle r ON j.id_rôle= r.id_rôle
        INNER JOIN film f ON f.id_film = j.id_film
        WHERE j.id_rôle= :idRole
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

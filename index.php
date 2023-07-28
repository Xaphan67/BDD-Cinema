<?php

// Utilise le controller CinemaController
use controller\CinemaController;

// Auto-load des classes
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

// Instancie le controller
$ctrlCinema = new CinemaController();

$id = isset($_GET["id"]) ? $_GET["id"] : null;

if (isset($_GET["action"])) // Action spécifiée - Affiche la page correspondante
{
    switch ($_GET["action"]) {
        case "listFilms": // Liste des films
            $ctrlCinema->listFilms();
            break;

        case "infoFilm": // Informations d'un film
            $ctrlCinema->infosFilm($id);
            break;

        case "listRealisateurs": // Liste des réalisateurs
            $ctrlCinema->listRealisateurs();
            break;

        case "infoRealisateur": // Information d'un réalisateur
            $ctrlCinema->infosRealisateur($id);
            break;

        case "formAddRealisateur": // Formulaire d'ajout d'un réalisateur
            $ctrlCinema->formAddRealisateur();
            break;

        case "addRealisateur": // Ajout d'un réalisateur
            $ctrlCinema->addRealisateur();
            break;

        case "formEditRealisateur": // Formulaire de modification d'un réalisateur
            $ctrlCinema->formEditRealisateur($id);
            break;

        case "editRealisateur": // Modification d'un réalisateur
            $ctrlCinema->editRealisateur($id);
            break;

        case "deleteRealisateur": // Suppression d'un réalisateur
            $ctrlCinema->deleteRealisateur($id);
            break;

        case "listActeurs": // Liste des acteurs
            $ctrlCinema->listActeurs();
            break;

        case "infoActeur": // Informations d'un acteur
            $ctrlCinema->infosActeur($id);
            break;

        case "listGenres": // Liste des genres
            $ctrlCinema->listGenres();
            break;

        case "infoGenre": // Informations d'un genre
            $ctrlCinema->infosGenre($id);
            break;

        case "formAddGenre": // Formulaire d'ajout d'un genre
            $ctrlCinema->formAddGenre();
            break;

        case "formEditGenre": // Formulaire de modification d'un genre
            $ctrlCinema->formEditGenre($id);
            break;

        case "editGenre": // Modification d'un genre
            $ctrlCinema->editGenre($id);
            break;

        case "addGenre": // Ajout d'un genre
            $ctrlCinema->addGenre();
            break;

        case "deleteGenre": // Suppression d'un genre
            $ctrlCinema->deleteGenre($id);
            break;

        case "listRoles": // Liste des roles
            $ctrlCinema->listRoles();
            break;

        case "infoRole": // Informations d'un rôle
            $ctrlCinema->infosRole($id);
            break;

        case "formAddRole": // Formulaire d'ajout d'un rôle
            $ctrlCinema->formAddRole();
            break;

        case "addRole": // Ajout d'un rôle
            $ctrlCinema->addRole();
            break;

        case "formEditRole": // Formulaire de modification d'un rôle
            $ctrlCinema->formEditRole($id);
            break;

        case "editRole": // Modification d'un rôle
            $ctrlCinema->editRole($id);
            break;

        case "deleteRole": // Suppression d'un rôle
            $ctrlCinema->deleteRole($id);
            break;
    }
} else // Aucune action spécifiée - Affiche la liste des films
{
    $ctrlCinema->listFilms();
}

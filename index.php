<?php session_start();

// Utilise les controllers
use controller\FilmsController;
use controller\RealisateursController;
use controller\ActeursController;
use controller\GenresController;
use controller\RolesController;

// Auto-load des classes
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

// Instancie les controllers
$ctrlFilms = new FilmsController();
$ctrlRealisateurs = new RealisateursController();
$ctrlActeurs = new ActeursController();
$ctrlGenres = new GenresController();
$ctrlRoles = new RolesController();

$id = isset($_GET["id"]) ? $_GET["id"] : null;
$acteur = isset($_GET["acteur"]) ? $_GET["acteur"] : null;
$role = isset($_GET["role"]) ? $_GET["role"] : null;

if (isset($_GET["action"])) // Action spécifiée - Affiche la page correspondante
{
    switch ($_GET["action"]) {
        case "listFilms": // Liste des films
            $ctrlFilms->listFilms();
            break;

        case "infoFilm": // Informations d'un film
            $ctrlFilms->infosFilm($id);
            break;

        case "formAddFilm": // Formulaire d'ajout d'un film
            $ctrlFilms->formAddFilm();
            break;

        case "addFilm": // Ajout d'un film
            $ctrlFilms->addFilm();
            break;

        case "formEditFilm": // Formulaire de modification d'un film
            $ctrlFilms->formEditFilm($id);
            break;

        case "editFilm": // Modification d'un film
            $ctrlFilms->editFilm($id);
            break;

        case "deleteFilm": // Suppression d'un film
            $ctrlFilms->deleteFilm($id);
            break;

        case "formAddCasting": // Formulaire d'ajout d'un acteur à un film
            $ctrlFilms->formAddCasting($id);
            break;

        case "addCasting": // Ajout d'un acteur à un film
            $ctrlFilms->addCasting($id);
            break;

        case "deleteCasting": // Suppression d'un acteur d'un film
            $ctrlFilms->deleteCasting($id, $acteur, $role);
            break;

        case "listRealisateurs": // Liste des réalisateurs
            $ctrlRealisateurs->listRealisateurs();
            break;

        case "infoRealisateur": // Information d'un réalisateur
            $ctrlRealisateurs->infosRealisateur($id);
            break;

        case "formAddRealisateur": // Formulaire d'ajout d'un réalisateur
            $ctrlRealisateurs->formAddRealisateur();
            break;

        case "addRealisateur": // Ajout d'un réalisateur
            $ctrlRealisateurs->addRealisateur();
            break;

        case "formEditRealisateur": // Formulaire de modification d'un réalisateur
            $ctrlRealisateurs->formEditRealisateur($id);
            break;

        case "editRealisateur": // Modification d'un réalisateur
            $ctrlRealisateurs->editRealisateur($id);
            break;

        case "deleteRealisateur": // Suppression d'un réalisateur
            $ctrlRealisateurs->deleteRealisateur($id);
            break;

        case "listActeurs": // Liste des acteurs
            $ctrlActeurs->listActeurs();
            break;

        case "infoActeur": // Informations d'un acteur
            $ctrlActeurs->infosActeur($id);
            break;

        case "formAddActeur": // Formulaire d'ajout d'un acteur
            $ctrlActeurs->formAddActeur();
            break;

        case "addActeur": // Ajout d'un acteur
            $ctrlActeurs->addActeur();
            break;

        case "formEditActeur": // Formulaire de modification d'un acteur
            $ctrlActeurs->formEditActeur($id);
            break;

        case "editActeur": // Modification d'un acteur
            $ctrlActeurs->editActeur($id);
            break;

        case "deleteActeur": // Supression d'un acteur
            $ctrlActeurs->deleteActeur($id);
            break;

        case "listGenres": // Liste des genres
            $ctrlGenres->listGenres();
            break;

        case "infoGenre": // Informations d'un genre
            $ctrlGenres->infosGenre($id);
            break;

        case "formAddGenre": // Formulaire d'ajout d'un genre
            $ctrlGenres->formAddGenre();
            break;

        case "formEditGenre": // Formulaire de modification d'un genre
            $ctrlGenres->formEditGenre($id);
            break;

        case "editGenre": // Modification d'un genre
            $ctrlGenres->editGenre($id);
            break;

        case "addGenre": // Ajout d'un genre
            $ctrlGenres->addGenre();
            break;

        case "deleteGenre": // Suppression d'un genre
            $ctrlGenres->deleteGenre($id);
            break;

        case "listRoles": // Liste des roles
            $ctrlRoles->listRoles();
            break;

        case "infoRole": // Informations d'un rôle
            $ctrlRoles->infosRole($id);
            break;

        case "formAddRole": // Formulaire d'ajout d'un rôle
            $ctrlRoles->formAddRole();
            break;

        case "addRole": // Ajout d'un rôle
            $ctrlRoles->addRole();
            break;

        case "formEditRole": // Formulaire de modification d'un rôle
            $ctrlRoles->formEditRole($id);
            break;

        case "editRole": // Modification d'un rôle
            $ctrlRoles->editRole($id);
            break;

        case "deleteRole": // Suppression d'un rôle
            $ctrlRoles->deleteRole($id);
            break;
    }
} else // Aucune action spécifiée - Affiche la liste des films
{
    $ctrlFilms->listFilms();
}

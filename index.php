<?php

// Utilise le controller CinemaController
use controller\CinemaController;

// Auto-load des classes
spl_autoload_register(function ($class_name)
{
    include $class_name . '.php';
});

// Instancie le controller
$ctrlCinema = new CinemaController();

if (isset($_GET["action"])) // Action spécifiée - Affiche la page correspondante
{
    switch($_GET["action"])
    {
        case "listFilms": // Liste des films
            $ctrlCinema->listFilms();
            
            break;
        
        case "listRealisateurs": // liste des réalisateurs
            $ctrlCinema->listRealisateurs();
            break;

        case "listActeurs": // liste des acteurs
            $ctrlCinema->listActeurs();
            break;

        case "listGenres": // liste des genres
            $ctrlCinema->listGenres();
            break;
        
        case "listRoles": // liste des roles
            $ctrlCinema->listRoles();
            break;
    }
}
else // Aucune action spécifiée - Affiche la liste des films
{
    $ctrlCinema->listFilms();
}
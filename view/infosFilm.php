<?php ob_start();

$infosfilm = $film->fetch();
$infosGenres = $genres->fetchALL(); ?>

<div class="main-box bg">
    <div class="film-info-side">
        <img class="affiche-large" src="public/img/posters/<?= $infosfilm["affiche_film"] ?>" alt="affiche"></img>
        <div class="film-info-actions">
            <button type="bouton" class="button">
                <a href="index.php?action=formEditFilm&id=<?= $infosfilm["id_film"] ?>">Modifier</a>
            </button>
            <button type="bouton" class="button">
                <a href="index.php?action=delteFilm&id=<?= $infosfilm["id_film"] ?>">Supprimer</a>
            </button>
        </div>
    </div>
    <div class="film-info-main">
        <h1><?= $infosfilm["titre_film"] ?></h1>
        <h2>Informations générales</h2>
        <hr>
        <p><span class="info">Date de sortie :</span> <?= $infosfilm["anneeSortie_film"] ?></p>
        <p><span class="info">Durée :</span> <?= $infosfilm["duree"] ?></p>
        <p><span class="info">Genres :</span> <?php 
        foreach($infosGenres as $key => $genre) {
            if ($key != count($infosGenres)- 1)
            {
                echo '<a href="index.php?action=infoGenre&id=' . $genre["id_genre_film"] . '">' . $genre["libelle_genre_film"] .", </a>";
            }
            else
            {
                echo '<a href="index.php?action=infoGenre&id=' . $genre["id_genre_film"] . '">' . $genre["libelle_genre_film"] ."</a>";
            }
        }
        ?></p>
        <p><span class="info">Note :</span>
            <?php
            for ($i = 1; $i <= $infosfilm["note_film"]; $i++) { ?>
                <img src="public/img/note_b.png" alt="note"></img>
            <?php }
            ?>
            <?php
            $greyStars = 5 - $infosfilm["note_film"];
            for ($i = 1; $i <= $greyStars; $i++) { ?>
                <img src="public/img/note_a.png" alt="note"></img>
            <?php }
            ?>
        </p>
        <h2>Casting</h2>
        <hr>
        <p><span class="info">Réalisateur :</span><a href="index.php?action=infoRealisateur&id=<?= $infosfilm["id_realisateur"] ?>"> <?= $infosfilm["realisateurFilm"] ?></a></p>
        <p><span class="info">Acteurs et actrices :</span></p>
        <?php foreach ($acteurs->fetchALL() as $acteur) { ?>
            <div class="acteur-casting">
                <p><a href="index.php?action=infoActeur&id=<?= $acteur["id_acteur"] ?>"><?= $acteur["acteurFilm"] ?></a>, en tant que <?= $acteur["nom_rôle"] ?></p>
                <button type="bouton" class="button button-round">
                    <a href="index.php?action=deleteCasting&id=<?= $infosfilm["id_film"] ?>&acteur=<?= $acteur["id_acteur"] ?>&role=<?= $acteur["id_rôle"] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                            <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                        </svg>
                    </a>
                </button>
            </div>
        <?php } ?>
        <button type="bouton" class="button button-large">
            <a href="index.php?action=formAddCasting&id=<?= $infosfilm["id_film"] ?>">Ajouter un acteur</a>
        </button>
        <h2>Synopsis</h2>
        <hr>
        <?= $infosfilm["synopsis_film"] ?>
    </div>
</div>

<?php

$titre = "Film : " . $infosfilm["titre_film"];
$titre_secondaire = "Films" . $infosfilm["titre_film"];
$contenu = ob_get_clean();

require "view/template.php";

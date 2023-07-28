<?php ob_start();

$infosfilm = $film->fetch(); ?>

<div class="main-box bg">
    <div class="film-info-side">
        <img class="affiche-large" src="public/img/posters/<?= $infosfilm["affiche_film"] ?>" alt="affiche"></img>
        <div class="film-info-actions">
            <button type="bouton" class="button">
                <a href="index.php?action=editFilm&id=<?= $infosfilm["id_film"] ?>">Modifier</a>
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
        <p><span class="info">Genres :</span> <?= $infosfilm["genres"] ?></p>
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
                <p><?= $acteur["acteurFilm"] ?>, en tant que <?= $acteur["nom_rôle"] ?></p>
                <button type="bouton" class="button button-round">
                    <a href="index.php?action=editActeur&id=<?= $acteur["id_acteur"] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                        </svg>
                    </a>
                </button>
                <button type="bouton" class="button button-round">
                    <a href="index.php?action=deleteActeur&id=<?= $acteur["id_acteur"] ?>">
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

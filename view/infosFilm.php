<?php ob_start();

$infosfilm = $film->fetch();
$infosGenres = $genres->fetchALL();
$infosActeurs = $acteurs->fetchALL(); ?>

<div class="section">
    <h1><?= $infosfilm["titre_film"] ?></h1>
    <div class="actions">
        <a href="index.php?action=formEditFilm&id=<?= $infosfilm["id_film"] ?>" title="Modifier le film">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
            </svg>
        </a>
        <a href="index.php?action=delteFilm&id=<?= $infosfilm["id_film"] ?>" title="Supprimer le film">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
            </svg>
        </a>
    </div>
</div>

<article class="article-main">
    <div class="content content-infos">
        <div class="picture">
            <img class="affiche-large" src="public/img/posters/<?= $infosfilm["affiche_film"] ?>" alt="affiche">
        </div>
        <div class="infos">
            <h2>Informations générales</h2>
            <ul class="list">
                <li>Année de sortie : <?= $infosfilm["anneeSortie_film"] ?></li>
                <li>Durée : <?= $infosfilm["duree"] ?></li>
                <li>Genres : <?php
                                foreach ($infosGenres as $key => $genre) {
                                    if ($key != count($infosGenres) - 1) {
                                        echo '<a href="index.php?action=infoGenre&id=' . $genre["id_genre_film"] . '">' . $genre["libelle_genre_film"] . ", </a>";
                                    } else {
                                        echo '<a href="index.php?action=infoGenre&id=' . $genre["id_genre_film"] . '">' . $genre["libelle_genre_film"] . "</a>";
                                    }
                                }
                                ?>
                </li>
                <li>Note :
                    <?php
                    for ($i = 1; $i <= $infosfilm["note_film"]; $i++) { ?>
                        <img src="public/img/note_b.png" alt="note">
                    <?php }
                    ?>
                    <?php
                    $greyStars = 5 - $infosfilm["note_film"];
                    for ($i = 1; $i <= $greyStars; $i++) { ?>
                        <img src="public/img/note_a.png" alt="note">
                    <?php }
                    ?>
                </li>
            </ul>
            <h2>Casting</h2>
            <ul class="list">
                <li>Réalisateur : <a href="index.php?action=infoRealisateur&id=<?= $infosfilm["id_realisateur"] ?>"> <?= $infosfilm["realisateurFilm"] ?></a></li>
                <li>Acteurs et actrices :</li>
                <li>
                    <div class="casting">
                        <div class ="casting-acteurs casting-section">
                            <?php foreach ($infosActeurs as $acteur) { ?>
                                <p><a href="index.php?action=infoActeur&id=<?= $acteur["id_acteur"] ?>"><?= $acteur["acteurFilm"] ?></a><br><?= $acteur["nom_rôle"] ?></p>
                            <?php } ?>
                        </div>
                        <div class="casting-section">
                            <?php foreach ($infosActeurs as $acteur) { ?>
                                <a class="casting-actions" href="index.php?action=deleteCasting&id=<?= $infosfilm["id_film"] ?>&acteur=<?= $acteur["id_acteur"] ?>&role=<?= $acteur["id_rôle"] ?>" title="Supprimer l'acteur">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                        <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                                    </svg>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="button" href="index.php?action=formAddCasting&id=<?= $infosfilm["id_film"] ?>">Ajouter un acteur</a>
                </li>
            </ul>
            <?php
            if ($infosfilm["synopsis_film"] != "") {
            ?>
                <h2>Synopsis</h2>
                <p><?= $infosfilm["synopsis_film"] ?></p>
            <?php } ?>
        </div>
    </div>
</article>

<?php
$title = "Wiki Films : Informations sur le film " . $infosfilm["titre_film"];
$description = "Découvrez toutes les informations sur le film " . $infosfilm["titre_film"];
$contenu = ob_get_clean();

require "view/template.php";

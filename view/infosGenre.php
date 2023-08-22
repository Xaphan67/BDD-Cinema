<?php ob_start();

$infosGenre = $genre->fetch();
$infosFilms = $films->fetchALL() ?>

<div class="section">
    <h1><?= $infosGenre["libelle_genre_film"] ?></h1>
    <p>Il existe <?= count($infosFilms) ?> film<?= count($infosFilms) > 1 ? "s" : "" ?> de ce genre</p>
    <div class="actions">
        <a href="index.php?action=formEditGenre&id=<?= $infosGenre["id_genre_film"] ?>" title="Modifier le genre">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
            </svg>
        </a>
    </div>
</div>
<article class="article-main">
    <div class="content">
        <div class="infos">
            <ul class="list-films">
                <?php foreach ($infosFilms as $film) { ?>
                    <li>
                        <div class="films">
                            <img class="affiche-small" src="public/img/posters/<?= $film["affiche_film"] ?>" alt="affiche">
                            <div class="infos-films">
                                <h2>
                                    <a href="index.php?action=infoFilm&id=<?= $film["IdFilm"] ?>"><?= $film["titre_film"] ?></a>
                                </h2>
                                <ul>
                                    <li><?= $film["anneeSortie_film"] ?></li>
                                    <li><?= $film["duree"] ?></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                <?php
                } ?>
            </ul>
        </div>
    </div>
</article>

<?php
$title = "Wiki Films : Informations sur le genre " . $infosGenre["libelle_genre_film"];
$description = "Découvrez toutes les informations sur le genre " . $infosGenre["libelle_genre_film"];
$contenu = ob_get_clean();

require "view/template.php";

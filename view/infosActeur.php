<?php ob_start();

$infosActeur = $acteur->fetch();
$infosFilms = $films->fetchALL(); ?>

<div class="section">
    <h1><?= $infosActeur["acteurFilm"] ?></h1>
    <div class="actions">
        <a href="index.php?action=formEditActeur&id=<?= $infosActeur["id_acteur"] ?>" title="Modifier l'acteur">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
            </svg>
        </a>
        <?php
        if (count($infosFilms) == 0) {
        ?>
            <a href="index.php?action=deleteActeur&id=<?= $infosActeur["id_acteur"] ?>" title="Supprimer l'acteur">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                    <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                </svg>
            </a>
        <?php
        } ?>
    </div>
</div>

<article class="article-main">
    <div class="content content-infos">
        <div class="picture">
            <figure>
                <img class="avatar-side" src="public/img/avatar.png" alt="avatar">
            </figure>
        </div>
        <div class="infos infos-right">
            <h2>Biographie</h2>
            <ul class="list">
                <li>Sexe : <?= $infosActeur["sexe_personne"] ?></li>
                <?php
                $datetime = new DateTime($infosActeur["dateNaissance_personne"]);
                $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::RELATIVE_LONG, IntlDateFormatter::NONE);
                ?>
                <li>Date de naissance : <?= $formatter->format($datetime) ?></li>
            </ul>
        </div>
        <div class="infos infos-full">
            <h2>Filmographie</h2>
            <div class="list-films-mini">
            <?php
                if (count($infosFilms) > 0) {
                ?>
                    <?php foreach ($infosFilms as $film) { ?>
                        <div class="films">
                            <figure>
                                <img class="affiche-small" src="public/img/posters/<?= $film["affiche_film"] ?>" alt="affiche">
                            </figure>
                            <div class="infos-films">
                                <h3><a href="index.php?action=infoFilm&id=<?= $film["id_film"] ?>"><?= $film["titre_film"] ?></a></h3>
                                <ul>
                                    <li><?= $film["anneeSortie_film"] ?></li>
                                    <li><?= $film["duree"] ?></li>
                                    <li>Dans le rôle de<br><a href="index.php?action=infoRole&id=<?= $film["id_rôle"] ?>"><?= $film["nom_rôle"] ?></a></li>
                                </ul>
                            </div>
                        </div>
                    <?php
                    } ?>
                <?php } else {
                ?>
                    <p><?= $infosActeur["sexe_personne"] == "Homme" ? "Ce réalisateur" : "Cette réalisatrice" ?> n'a réalisé aucun film</p>
                <?php } ?>
            </div>
        </div>
    </div>
</article>

<?php
$title = "Wiki Films : Informations sur l'acteur " . $infosActeur["acteurFilm"];
$description = "Découvrez toutes les informations sur l'acteur " . $infosActeur["acteurFilm"];
$contenu = ob_get_clean();

require "view/template.php";

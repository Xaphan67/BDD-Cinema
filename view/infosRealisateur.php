<?php ob_start();

$infosRealisateur = $realisateur->fetch();
$infosFilms = $films->fetchALL(); ?>

<article class="article-main">
    <div class="content content-no-bg">
        <img class="avatar" src="public/img/avatar.png" alt="avatar">
        <div class="infos">
            <h1><?= $infosRealisateur["realisateurFilm"] ?></h1>
            <h2>Biographie</h2>
            <ul class="list">
                <li>Sexe : <?= $infosRealisateur["sexe_personne"] ?></li>
                <?php
                $datetime = new DateTime($infosRealisateur["dateNaissance_personne"]);
                $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::RELATIVE_LONG, IntlDateFormatter::NONE);
                ?>
                <li>Date de naissance : <?= $formatter->format($datetime) ?></li>
            </ul>
            <h2>Filmographie</h2>
            <?php
            if (count($infosFilms) > 0) {
            ?>
                <ul class="list-films">
                    <?php foreach ($infosFilms as $film) { ?>
                        <li>
                            <div class="films">
                                <img class="affiche-small" src="public/img/posters/<?= $film["affiche_film"] ?>" alt="affiche">
                                <div class="infos-films">
                                    <h3><a href="index.php?action=infoFilm&id=<?= $film["IdFilm"] ?>"><?= $film["titre_film"] ?></a></h3>
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
            <?php } else {
            ?>
                <p><?= $infosRealisateur["sexe_personne"] == "Homme" ? "Ce réalisateur" : "Cette réalisatrice" ?> n'a réalisé aucun film</p>
            <?php } ?>
        </div>
        <div class="actions">
            <a href="index.php?action=formEditRealisateur&id=<?= $infosRealisateur["id_realisateur"] ?>" title="Modifier le réalisateur">
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                </svg>
            </a>
            <?php
            if (count($infosFilms) == 0) {
            ?>
                <a href="index.php?action=deleteRealisateur&id=<?= $infosRealisateur["id_realisateur"] ?>" title="Supprimer le réalisateur">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                        <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                    </svg>
                </a>
            <?php
            } ?>
        </div>
    </div>
</article>

<?php
$title = "Wiki Films : Informations sur le réalisateur " . $infosRealisateur["realisateurFilm"];
$description = "Découvrez toutes les informations sur le réalisateur " . $infosRealisateur["realisateurFilm"];
$contenu = ob_get_clean();

require "view/template.php";

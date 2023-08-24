<?php ob_start();

$infosRole = $role->fetch();
$infosActeurs = $acteurs->fetchALL(); ?>

<div class="section">
    <h1><?= $infosRole["nom_rôle"] ?></h1>
    <p>Il existe <?= count($infosActeurs) ?> acteur<?= count($infosActeurs) > 1 ? "s" : "" ?> ayant joué ce rôle</p>
    <div class="actions">
        <a href="index.php?action=formEditRole&id=<?= $infosRole["id_rôle"] ?>" title="Modifier le rôle">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
            </svg>
        </a>
    </div>
</div>

<div class="list-articles">
    <?php foreach ($infosActeurs as $acteur) {
    ?>
        <article>
            <div class="content">
                <figure>
                    <img class="avatar" src="public/img/avatar.png" alt="avatar">
                </figure>
                <div class="infos">
                    <h2><a href="index.php?action=infoActeur&id=<?= $acteur["id_acteur"] ?>"><?= $acteur["acteurFilm"] ?></a></h2>
                    <p><?= $acteur["sexe_personne"] ?></p>
                    <?php
                    $datetime = new DateTime($acteur["dateNaissance_personne"]);
                    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::RELATIVE_LONG, IntlDateFormatter::NONE);
                    ?>
                    <p>Né<?= $acteur["sexe_personne"] == "Femme" ? "e" : "" ?> le <?= $formatter->format($datetime) ?></p>
                    <p>Joue dans le film<br><a href="index.php?action=infoFilm&id=<?= $acteur["id_film"] ?>"><?= $acteur["titre_film"] ?></a></p>
                </div>
                <div class="actions">
                    <a href="index.php?action=formEditActeur&id=<?= $acteur["id_acteur"] ?>" title="Modifier l'acteur">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                        </svg>
                    </a>
                </div>
            </div>
        </article>
    <?php } ?>
</div>

<?php $title = "Wiki Films : Informations sur le rôle " . $infosRole["nom_rôle"];
$description = "Découvrez toutes les informations sur le rôle " . $infosRole["nom_rôle"];
$contenu = ob_get_clean();

require "view/template.php";

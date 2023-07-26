<?php ob_start();

foreach ($requete->fetchALL() as $acteur) {
?>
    <div class="personne-box bg">
        <img src="public/img/avatar.png" class="avatar"></img>
        <div class="personne-main">
            <h1><a href="index.php?action=infoActeur&id=<?= $acteur["id_acteur"] ?>"><?= $acteur["acteurFilm"] ?></a></h1>
            <p><?= $acteur["sexe_personne"] ?></p>
            <?php
            $datetime = new DateTime($acteur["dateNaissance_personne"]);
            $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::RELATIVE_LONG, IntlDateFormatter::NONE);
            ?>
            <p>Né<?= $acteur["sexe_personne"] == "Femme" ? "e" : "" ?> le <?= $formatter->format($datetime) ?></p>
        </div>
    </div>
<?php }

$titre = "Liste des acteurs";
$titre_secondaire = "Liste des acteurs";
$contenu = ob_get_clean();

require "view/template.php";

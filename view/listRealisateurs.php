<?php ob_start();

foreach ($requete->fetchALL() as $realisateur) {
?>
    <div class="personne-box">
        <img src="public/img/avatar.png" class="avatar"></img>
        <div class="personne-main">
            <h1><a href="index.php?action=infoRealisateur&id=<?= $realisateur["id_personne"] ?>"><?= $realisateur["realisateurFilm"] ?></a></h1>
            <p><?= $realisateur["sexe_personne"] ?></p>
            <?php
            $datetime = new DateTime($realisateur["dateNaissance_personne"]);
            $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::RELATIVE_LONG, IntlDateFormatter::NONE);
            ?>
            <p>Né<?php $realisateur["sexe_personne"] == "Femme" ? "e" : "" ?> le <?= $formatter->format($datetime) ?></p>
        </div>
    </div>
<?php }

$titre = "Liste des réalisateurs";
$titre_secondaire = "Liste des réalisateurs";
$contenu = ob_get_clean();

require "view/template.php";

<?php ob_start();

foreach($films->fetchALL() as $film)
{
    ?>
    <div class="film-box">
        <div class="film-side">
            <img src="public/img/<?= $film["affiche_film"] ?>" alt="affiche"></img></br>
            <?= $film["note_film"] ?>
        </div>
        <div class="film-main">
            <?= $film["titre_film"] ?></br>
            <?= $film["anneeSortie_film"] . " / " . $film["duree"] . " / " . $film["genres"]?></br>
            De : <?= $film["realisateurFilm"] ?></br>
            Avec : <?= $film["acteursFilm"] ?></br>
            <?=$film["synopsis_film"] ?>
        </div>
    </div>
<?php } 

$titre = "Liste des films";
$titre_secondaire = "Liste des films";
$contenu = ob_get_clean();

require "view/template.php";
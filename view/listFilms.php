<?php ob_start();

foreach ($films->fetchALL() as $film) {
?>
    <div class="film-box">
        <div class="film-side">
            <img class="affiche" src="public/img/posters/<?= $film["affiche_film"] ?>" alt="affiche"></img>
            <div class="note">
                <?php
                for ($i = 1; $i <= $film["note_film"]; $i++) { ?>
                    <img src="public/img/note_b.png" alt="note"></img>
                <?php }
                ?>
                <?php
                $greyStars = 5 - $film["note_film"];
                for ($i = 1; $i <= $greyStars; $i++) { ?>
                    <img src="public/img/note_a.png" alt="note"></img>
                <?php }
                ?>
            </div>
        </div>
        <div class="film-main">
            <h1><a href="index.php?action=infoFilm&id=<?= $film["IdFilm"] ?>"><?= $film["titre_film"] ?></a></h1>
            <p><?= $film["anneeSortie_film"] . " / " . $film["duree"] . " / " . $film["genres"] ?></p>
            <p><span class="info">De :</span><a href="index.php?action=infoRealisateur$id=<?= $film["id_personne"] ?>"> <?= $film["realisateurFilm"] ?></a></p>
            <p><span class="info">Avec :</span> <?= $film["acteursFilm"] ?></p>
            <p><?= $film["synopsis_film"] ?></p>
        </div>
    </div>
<?php }

$titre = "Liste des films";
$titre_secondaire = "Liste des films";
$contenu = ob_get_clean();

require "view/template.php";

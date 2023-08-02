<?php ob_start();

$infosRealisateur = $realisateur->fetch();
$infosGenres = $genres->fetchALL();
$infosActeurs = $acteurs->fetchAll(); ?>

<div class="main-box bg">
    <div class="personne-info-side">
        <img src="public/img/avatar.png" class="avatar-large"></img>
    </div>
    <div class="personne-info-main">
        <h1><?= $infosRealisateur["realisateurFilm"] ?></h1>
        <h2>Biographie</h2>
        <hr>
        <p><span class="info">Sexe :</span> <?= $infosRealisateur["sexe_personne"] ?></p>
        <?php
        $datetime = new DateTime($infosRealisateur["dateNaissance_personne"]);
        $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::RELATIVE_LONG, IntlDateFormatter::NONE);
        ?>
        <p><span class="info">Date de naissance :</span> <?= $formatter->format($datetime) ?></p>
        <div class="film-info-actions">
            <a href="index.php?action=formEditRealisateur&id=<?= $infosRealisateur["id_realisateur"] ?>">
                <button type="bouton" class="button">
                    Modifier
                </button>
            </a>
            <a href="index.php?action=deleteRealisateur&id=<?= $infosRealisateur["id_realisateur"] ?>">
                <button type="bouton" class="button">
                    Supprimer
                </button>
            </a>
        </div>
    </div>
</div>

<?php foreach ($films->fetchALL() as $film) {
?>
    <div class="film-box bg">
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
            <p><?= $film["anneeSortie_film"] . " / " . $film["duree"] . " / " ?>
            <?php 
                $liensGenres = "";
                foreach($infosGenres as $key => $genre) {
                    if ($genre["id_film"] == $film['IdFilm'])
                    {
                        $liensGenres .= '<a href="index.php?action=infoGenre&id=' . $genre["id_genre_film"] . '">' . $genre["libelle_genre_film"] ."</a>, ";
                    }
                }
                
                // Retire la dernière virgule à la fin de la liste des genres
                $liensGenres = substr($liensGenres, 0, -6); // Retire '</a> ,' à la fin de la chaîne (6 caractères)
                $liensGenres .= "</a>"; // Rajoute '</a>' à la fin de la chaîne
                
                echo $liensGenres; ?>
            </p>
            <p><span class="info">De :</span><a href="index.php?action=infoRealisateur&id=<?= $infosRealisateur["id_realisateur"] ?>"> <?= $infosRealisateur["realisateurFilm"] ?></a></p>
            <p><span class="info">Avec :</span>
            <?php 
                $liensActeurs = "";
                foreach($infosActeurs as $key => $acteur) {
                    if ($acteur["id_film"] == $film['IdFilm'])
                    {
                        $liensActeurs .= '<a href="index.php?action=infoActeur&id=' . $acteur["id_acteur"] . '">' . $acteur["acteurFilm"] ."</a>, ";
                    }
                }
                
                // Retire la dernière virgule à la fin de la liste des acteurs
                $liensActeurs = substr($liensActeurs, 0, -6); // Retire '</a> ,' à la fin de la chaîne (6 caractères)
                $liensActeurs .= "</a>"; // Rajoute '</a>' à la fin de la chaîne
                
                echo $liensActeurs; ?>
            </p>
            <p><?= $film["synopsis_film"] ?></p>
            <a href="index.php?action=editFilm&id=<?= $film["IdFilm"] ?>">
                <button type="bouton" class="button button-round">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                        <path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z" />
                    </svg>
                </button>
            </a>
            <a href="index.php?action=deleteFilm&id=<?= $film["IdFilm"] ?>">
                <button type="bouton" class="button button-round">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                        <path d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z" />
                    </svg>
                </button>
            </a>
        </div>
    </div>
<?php }

$titre = "Réalisateur : " . $infosRealisateur["realisateurFilm"];
$titre_secondaire = "Réalisateur : " . $infosRealisateur["realisateurFilm"];
$contenu = ob_get_clean();

require "view/template.php";

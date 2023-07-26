<?php ob_start();

foreach ($requete->fetchALL() as $genre) {
?>
    <div class="genre-role-box bg">
        <h1><a href="index.php?action=infoGenre&id=<?= $genre["id_genre_film"] ?>"><?= $genre["libelle_genre_film"] ?></a></h1>
        <p>Il existe <?= $genre["nbFilms"] ?> film<?= $genre["nbFilms"] > 1 ? "s" : "" ?> de ce genre</p>
    </div>
<?php }

$titre = "Liste des genres";
$titre_secondaire = "Liste des genres";
$contenu = ob_get_clean();

require "view/template.php";

<?php ob_start();

$infosFilm = $film->fetch();
$infosGenresSelected = $genresSelected->fetchALL();
$genresFilm = [];
for ($i = 0; $i < count($infosGenresSelected); $i++) {
    $genresFilm[] = $infosGenresSelected[$i][0];
} ?>

<div class="main-box-titre bg">
    <h1>Modifier un film</h1>
    <p>Merci de remplir tous les champs pour modifier le film.</p>
    <form action="index.php?action=editFilm&id=<?= $infosFilm["id_film"] ?>" method="post" enctype="multipart/form-data">
        <div class=" form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre" value="<?= $infosFilm["titre_film"] ?>" required>
        </div>
        <div class=" form-group">
            <label for="annee">Année de sortie *</label>
            <input type="number" id="annee" name="annee" value="<?= $infosFilm["anneeSortie_film"] ?>" required>
        </div>
        <div class="form-group">
            <label for="duree">Durée (minutes) *</label>
            <input type="number" id="duree" name="duree" value="<?= $infosFilm["duree_film"] ?>" required>
        </div>
        <div class="form-group">
            <label for="synopsis">Synopsis</label>
            <textarea id="synopsis" name="synopsis" rows="5"><?= $infosFilm["synopsis_film"] ?></textarea>
        </div>
        <div class="form-group">
            <label for="realisateur">Réalisateur *</label>
            <select id="realisateur" name="realisateur" class="select-right" required>
                <?php
                foreach ($realisateurs->fetchALL() as $realisateur) {
                ?>
                    <option value="<?= $realisateur["id_realisateur"] ?>" <?= $infosFilm["id_realisateur"] == $realisateur["id_realisateur"] ? "selected" : "" ?>><?= $realisateur["realisateurFilm"] ?></option>
                <?php }
                ?>
            </select>
            <button type="bouton" class="button-round">
                <a href="index.php?action=formAddRealisateur">
                    +
                </a>
            </button>
        </div>
        <div class="form-group">
            <label for="genres">Genres *</label>
            <select id="genres" name="genres[]" class="select-right" multiple="multiple" required>
                <?php
                foreach ($genres->fetchALL() as $genre) {
                ?>
                    <option value="<?= $genre["id_genre_film"] ?>" <?= in_array($genre["id_genre_film"], $genresFilm) ? "selected" : "" ?>><?= $genre["libelle_genre_film"] ?></option>
                <?php }
                ?>
            </select>
            <button type="bouton" class="button-round">
                <a href="index.php?action=formAddGenre">
                    +
                </a>
            </button>
        </div>
        <img class="affiche-small" src="public/img/posters/<?= $infosFilm["affiche_film"] ?>" alt="affiche"></img>
        <div class="form-group">
            <label>Affiche</label>
            <input type="file" id="affiche" name="affiche">
            <label for="affiche" class="affiche-upload">Modifier</label>
        </div>
        <div class="form-group">
            <label for="note">Note *</label>
            <input type="number" id="note" name="note" min="0" max="5" value="<?= $infosFilm["note_film"] ?>" required>
        </div>
        <button type="submit" class="button-submit" name="submit">Modifier</button>
    </form>
</div>

<?php



$titre = "Modifier un film";
$titre_secondaire = "Modifier un film";
$contenu = ob_get_clean();

require "view/template.php";

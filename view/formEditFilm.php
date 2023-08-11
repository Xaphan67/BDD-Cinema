<?php ob_start();

$infosFilm = $film->fetch();
$infosGenresSelected = $genresSelected->fetchALL();
$genresFilm = [];
for ($i = 0; $i < count($infosGenresSelected); $i++) {
    $genresFilm[] = $infosGenresSelected[$i][0];
} ?>

<div class="section">
    <h1>Modifier un film</h1>
    <p>Merci de remplir tous les champs pour modifier le film.</p>
</div>

<article class="article-main">
    <div class="content content-no-bg">
        <form class="form-col" action="index.php?action=editFilm&id=<?= $infosFilm["id_film"] ?>" method="post" enctype="multipart/form-data">
            <div class=" form-group form-group-col form-titre">
                <label for="titre">Titre *</label>
                <input type="text" id="titre" name="titre" value="<?= $infosFilm["titre_film"] ?>" required>
            </div>
            <div class=" form-group form-group-col form-annee">
                <label for="annee">Année de sortie *</label>
                <input type="number" id="annee" name="annee" value="<?= $infosFilm["anneeSortie_film"] ?>" required>
            </div>
            <div class="form-group form-group-col form-duree">
                <label for="duree">Durée (minutes) *</label>
                <input type="number" id="duree" name="duree" value="<?= $infosFilm["duree_film"] ?>" required>
            </div>
            <div class="form-group form-group-col form-real">
                <label for="realisateur">Réalisateur *</label>
                <div class="select-button">
                    <select id="realisateur" name="realisateur" class="select-right" required>
                        <?php
                        foreach ($realisateurs->fetchALL() as $realisateur) {
                        ?>
                            <option value="<?= $realisateur["id_realisateur"] ?>" <?= $infosFilm["id_realisateur"] == $realisateur["id_realisateur"] ? "selected" : "" ?>><?= $realisateur["realisateurFilm"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <a class="button casting-actions" href="index.php?action=formAddRealisateur" class="form-button button-round">+</a>
                </div>
            </div>
            <div class="form-group form-group-col form-genres">
                <label for="genres">Genres *</label>
                <div class="select-button">
                    <select id="genres" name="genres[]" class="select-right" multiple="multiple" required>
                        <?php
                        foreach ($genres->fetchALL() as $genre) {
                        ?>
                            <option value="<?= $genre["id_genre_film"] ?>" <?= in_array($genre["id_genre_film"], $genresFilm) ? "selected" : "" ?>><?= $genre["libelle_genre_film"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <a class="button casting-actions" href="index.php?action=formAddGenre" class="form-button button-round">+</a>
                    </a>
                </div>
            </div>
            <div class="form-group form-group-col form-affiche">
                <label>Affiche *</label>
                <div class="select-button">
                    <img class="affiche-small" src="public/img/posters/<?= $infosFilm["affiche_film"] ?>" alt="affiche"></img>
                    <input type="file" id="affiche" name="affiche">
                    <label for="affiche" class="button casting-actions">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                            <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                        </svg>
                    </label>
                </div>
            </div>
            <div class="form-group form-group-col form-note">
                <label for="note">Note *</label>
                <input type="number" id="note" name="note" min="0" max="5" value="<?= $infosFilm["note_film"] ?>" required>
            </div>
            <div class="form-group form-group-col form-synopsis">
                <label for="synopsis">Synopsis</label>
                <textarea id="synopsis" name="synopsis" rows="5"></textarea>
            </div>
            <button type="submit" class="button-submit form-submit form-submit-order" name="submit">Modifier</button>
        </form>
    </div>
</article>

<?php

$title = "Wiki Films : Modifier un film";
$description = "Formulaire d'édition d'un film";
$contenu = ob_get_clean();

require "view/template.php";

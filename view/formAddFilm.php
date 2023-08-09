<?php ob_start(); ?>

<div class="section">
    <h1>Ajouter un film</h1>
    <p>Merci de remplir tous les champs pour ajouter un film</p>
</div>

<article class="article-main">
    <div class="content content-no-bg">
        <form action="index.php?action=addFilm" method="post" enctype="multipart/form-data">
            <div class=" form-group">
                <label for="titre">Titre *</label>
                <input type="text" id="titre" name="titre" required>
            </div>
            <div class="form-group">
                <label for="annee">Année de sortie *</label>
                <input type="number" id="annee" name="annee" required>
            </div>
            <div class="form-group">
                <label for="duree">Durée (minutes) *</label>
                <input type="number" id="duree" name="duree" required>
            </div>
            <div class="form-group">
                <label for="realisateur">Réalisateur *</label>
                <div class="select-button">
                    <select id="realisateur" name="realisateur" class="select-right" required>
                        <?php
                        foreach ($realisateurs->fetchALL() as $realisateur) {
                        ?>
                            <option value="<?= $realisateur["id_realisateur"] ?>"><?= $realisateur["realisateurFilm"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <a class="button casting-actions" href="index.php?action=formAddRealisateur" class="form-button button-round">+</a>
                </div>
            </div>
            <div class="form-group">
                <label for="genres">Genres *</label>
                <div class="select-button">
                    <select id="genres" name="genres[]" class="select-right" multiple="multiple" required>
                        <?php
                        foreach ($genres->fetchALL() as $genre) {
                        ?>
                            <option value="<?= $genre["id_genre_film"] ?>"><?= $genre["libelle_genre_film"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <a class="button casting-actions" href="index.php?action=formAddGenre" class="form-button button-round">+</a>
                    </a>
                </div>
            </div>
            <div class="form-group">
                <label>Affiche *</label>
                <div class="select-button">
                    <input type="file" id="affiche" name="affiche" required>
                    <label for="affiche" class="button casting-actions">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                            <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                        </svg>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="note">Note *</label>
                <input type="number" id="note" name="note" min="0" max="5" value="3">
            </div>
            <div class="form-group">
                <label for="synopsis">Synopsis</label>
                <textarea id="synopsis" name="synopsis" rows="5"></textarea>
            </div>
            <button type="submit" class="button-submit" name="submit">Ajouter</button>
        </form>
    </div>
</article>

<?php

$title = "Ajouter un film";
$contenu = ob_get_clean();

require "view/template.php";

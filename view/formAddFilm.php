<?php ob_start(); ?>

<div class="main-box-titre bg">
    <h1>Ajouter un film</h1>
    <p>Merci de remplir tous les champs pour ajouter un film.</p>
    <form action="index.php?action=addFilm" method="post" enctype="multipart/form-data">
        <div class=" form-group">
            <label for="titre">Titre *</label>
            <input type="text" id="titre" name="titre">
        </div>
        <div class="form-group">
            <label for="annee">Année de sortie *</label>
            <input type="number" id="annee" name="annee">
        </div>
        <div class="form-group">
            <label for="duree">Durée (minutes) *</label>
            <input type="number" id="duree" name="duree">
        </div>
        <div class="form-group">
            <label for="synopsis">Synopsis</label>
            <textarea id="synopsis" name="synopsis" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label for="realisateur">Réalisateur *</label>
            <select id="realisateur" name="realisateur" class="select-right">
                <?php
                foreach ($realisateurs->fetchALL() as $realisateur) {
                ?>
                    <option value="<?= $realisateur["id_realisateur"] ?>"><?= $realisateur["realisateurFilm"] ?></option>
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
            <select id="genres" name="genres[]" class="select-right" multiple="multiple">
                <?php
                foreach ($genres->fetchALL() as $genre) {
                ?>
                    <option value="<?= $genre["id_genre_film"] ?>"><?= $genre["libelle_genre_film"] ?></option>
                <?php }
                ?>
            </select>
            <button type="bouton" class="button-round">
                <a href="index.php?action=formAddGenre">
                    +
                </a>
            </button>
        </div>
        <div class="form-group">
            <label>Affiche *</label>
            <input type="file" id="affiche" name="affiche">
            <label for="affiche" class="affiche-upload">Choisir...</label>
        </div>
        <div class="form-group">
            <label for="note">Note *</label>
            <input type="number" id="note" name="note" min="0" max="5" value="3">
        </div>
        <button type="submit" class="button-submit" name="submit">Ajouter</button>
    </form>
</div>

<?php

$titre = "Ajouter un film";
$titre_secondaire = "Ajouter un film";
$contenu = ob_get_clean();

require "view/template.php";

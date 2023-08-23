<?php ob_start(); ?>

<div class="section">
    <h1>Ajouter un film</h1>
    <p>Merci de remplir tous les champs pour ajouter un film</p>
</div>

<div class="article-main">
    <div class="content content-no-bg">
        <?php
        if (isset($_SESSION["message"])) {
            switch ($_SESSION['message'][0]) {
                case "Error":
                    $message = "Certains champs sont vides ou invalides";
                    break;
                case "ExtError":
                    $message = "L'extension du fichier n'est pas valide.<br>Extensions acceptées : .jpg, .jpeg, .png";
                    break;
                case "SizeError":
                    $message = "La taille du fichier est trop grande";
                    break;
                case "FileError":
                    $message = "Une erreur est survenue pendant l'upload du fichier";
                    break;
            } ?>
            <div class="error">
                <svg xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512">
                    <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-384c13.3 0 24 10.7 24 24V264c0 13.3-10.7 24-24 24s-24-10.7-24-24V152c0-13.3 10.7-24 24-24zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z" />
                </svg>
                <p><?= $message ?></p>
            </div>
        <?php }

        // Supprime le message après avoir affiché l'alert.
        unset($_SESSION['message']);
        ?>

        <form class="form-col" action="index.php?action=addFilm" method="post" enctype="multipart/form-data">
            <div class=" form-group form-group-col form-titre">
                <label for="titre">Titre *</label>
                <input type="text" id="titre" name="titre" required>
            </div>
            <div class="form-group form-group-col form-annee">
                <label for="annee">Année de sortie *</label>
                <input type="number" id="annee" name="annee" required>
            </div>
            <div class="form-group form-group-col form-duree">
                <label for="duree">Durée (minutes) *</label>
                <input type="number" id="duree" name="duree" required>
            </div>
            <div class="form-group form-group-col form-real">
                <label for="realisateur">Réalisateur *</label>
                <div class="select-button">
                    <select id="realisateur" name="realisateur" class="select-right" required>
                        <option value="">Veuillez sélectionner un réalisateur</option>
                        <?php
                        foreach ($realisateurs->fetchALL() as $realisateur) {
                        ?>
                            <option value="<?= $realisateur["id_realisateur"] ?>"><?= $realisateur["realisateurFilm"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <a class="button form-button button-round" href="index.php?action=formAddRealisateur" title="Ajouter un réalisateur">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                            <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="form-group form-group-col form-genres">
                <label for="genres">Genres *</label>
                <div class="select-button-multiplle">
                    <select id="genres" name="genres[]" class="select-right" multiple="multiple" required>
                        <?php
                        foreach ($genres->fetchALL() as $genre) {
                        ?>
                            <option value="<?= $genre["id_genre_film"] ?>"><?= $genre["libelle_genre_film"] ?></option>
                        <?php }
                        ?>
                    </select>
                    <a class="button form-button button-round" href="index.php?action=formAddGenre" title="Ajouter un genre">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                            <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="form-group form-group-col form-affiche">
                <label>Affiche *</label>
                <div class="select-button">
                    <input type="file" id="affiche" name="affiche" required>
                    <label for="affiche" class="button casting-actions" title="Choisir une affiche">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                            <path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128z" />
                        </svg>
                    </label>
                </div>
            </div>
            <div class="form-group form-group-col form-note">
                <label for="note">Note *</label>
                <input type="number" id="note" name="note" min="0" max="5" value="3">
            </div>
            <div class="form-group form-group-col form-synopsis">
                <label for="synopsis">Synopsis</label>
                <textarea id="synopsis" name="synopsis" rows="5"></textarea>
            </div>
            <button type="submit" class="button-submit form-submit form-submit-order" name="submit">Ajouter</button>
        </form>
    </div>
</div>

<?php

$title = "Wiki Films : Ajouter un film";
$description = "Formulaire d'ajout d'un film";
$contenu = ob_get_clean();

require "view/template.php";

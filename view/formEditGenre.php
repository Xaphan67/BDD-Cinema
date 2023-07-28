<?php ob_start();

$infosGenre = $genre->fetch(); ?>

<div class="main-box-titre bg">
    <h1>Modifier un genre</h1>
    <p>Merci de remplir tous les champs pour modifier le genre.</p>
    <form action="index.php?action=editGenre&id=<?= $infosGenre["id_genre_film"] ?>" method="post">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" value="<?= $infosGenre["libelle_genre_film"] ?>">
        </div>
        <button type=" submit" class="button-submit" name="submit">Modifier</button>
    </form>
</div>

<?php

$titre = "Modifier le genre";
$titre_secondaire = "Modifier le genre";
$contenu = ob_get_clean();

require "view/template.php";

<?php ob_start();

$infosGenre = $genre->fetch(); ?>

<div class="section">
    <h1>Modifier un genre</h1>
    <p>Merci de remplir tous les champs pour modifier le genre</p>
</div>

<div class="article-main">
    <div class="content content-no-bg">
        <form action="index.php?action=editGenre&id=<?= $infosGenre["id_genre_film"] ?>" method="post">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= $infosGenre["libelle_genre_film"] ?>" required>
            </div>
            <button type="submit" name="submit">Modifier</button>
        </form>
    </div>
</div>

<?php

$title = "Wiki Films : Modifier un genre";
$description = "Formulaire d'édition d'un genre";
$contenu = ob_get_clean();

require "view/template.php";

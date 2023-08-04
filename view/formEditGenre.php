<?php ob_start();

$infosGenre = $genre->fetch(); ?>

<div class="section">
    <h1>Modifier un genre</h1>
    <p>Merci de remplir tous les champs pour modifier le genre</p>
</div>

<article>
    <div class="content content-no-bg">
        <form action="index.php?action=editGenre&id=<?= $infosGenre["id_genre_film"] ?>" method="post">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= $infosGenre["libelle_genre_film"] ?>" required>
            </div>
            <button type="submit" name="submit">Modifier</button>
        </form>
    </div>
</article>

<?php

$title = "Modifier le genre";
$contenu = ob_get_clean();

require "view/template.php";

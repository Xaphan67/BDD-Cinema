<?php ob_start(); ?>

<div class="section">
    <h1>Ajouter un genre</h1>
    <p>Merci de remplir tous les champs pour ajouter un genre</p>
</div>

<article class="article-main">
    <div class="content content-no-bg">
        <form action="index.php?action=addGenre" method="post">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <button type="submit" name="submit">Ajouter</button>
        </form>
    </div>
</article>

<?php

$title = "Wiki Films : Ajouter un genre";
$description = "Formulaire d'ajout d'un genre";
$contenu = ob_get_clean();

require "view/template.php";

<?php ob_start(); ?>

<div class="main-box-titre bg">
    <h1>Ajouter un genre</h1>
    <p>Merci de remplir tous les champs pour ajouter un genre.</p>
    <form action="index.php?action=addGenre" method="post">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom">
        </div>
        <button type="submit" class="button-submit" name="submit">Ajouter</button>
    </form>
</div>

<?php

$titre = "Ajouter un genre";
$titre_secondaire = "Ajouter un genre";
$contenu = ob_get_clean();

require "view/template.php";

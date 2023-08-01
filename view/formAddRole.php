<?php ob_start(); ?>

<div class="main-box-titre bg">
    <h1>Ajouter un rôle</h1>
    <p>Merci de remplir tous les champs pour ajouter un rôle.</p>
    <form action="index.php?action=addRole" method="post">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <button type="submit" class="button-submit" name="submit">Ajouter</button>
    </form>
</div>

<?php

$titre = "Ajouter un rôle";
$titre_secondaire = "Ajouter un rôle";
$contenu = ob_get_clean();

require "view/template.php";

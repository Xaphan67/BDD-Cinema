<?php ob_start();

$infosRole = $role->fetch(); ?>

<div class="main-box-titre bg">
    <h1>Modifier un rôle</h1>
    <p>Merci de remplir tous les champs pour modifier le rôle.</p>
    <form action="index.php?action=editRole&id=<?= $infosRole["id_rôle"] ?>" method="post">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" value="<?= $infosRole["nom_rôle"] ?>" required>
        </div>
        <button type="submit" class="button-submit" name="submit">Modifier</button>
    </form>
</div>

<?php

$titre = "Modifier le rôle";
$titre_secondaire = "Modifier le rôle";
$contenu = ob_get_clean();

require "view/template.php";

<?php ob_start();

$infosRole = $role->fetch(); ?>

<div class="section">
    <h1>Modifier un rôle</h1>
    <p>Merci de remplir tous les champs pour modifier le rôle</p>
</div>

<div class="article-main">
    <div class="content content-no-bg">
        <form action="index.php?action=editRole&id=<?= $infosRole["id_rôle"] ?>" method="post">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= $infosRole["nom_rôle"] ?>" required>
            </div>
            <button type="submit" name="submit">Modifier</button>
        </form>
    </div>
</div>

<?php

$title = "Wiki Films : Modifier un rôle";
$description = "Formulaire d'édition d'un rôle";
$contenu = ob_get_clean();

require "view/template.php";

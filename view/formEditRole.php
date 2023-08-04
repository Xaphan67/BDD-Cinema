<?php ob_start();

$infosRole = $role->fetch(); ?>

<div class="section">
    <h1>Modifier un rôle</h1>
    <p>Merci de remplir tous les champs pour modifier le rôle</p>
</div>

<article>
    <div class="content content-no-bg">
        <form action="index.php?action=editRole&id=<?= $infosRole["id_rôle"] ?>" method="post">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= $infosRole["nom_rôle"] ?>" required>
            </div>
            <button type="submit" name="submit">Modifier</button>
        </form>
    </div>
</article>

<?php

$title = "Modifier le rôle";
$contenu = ob_get_clean();

require "view/template.php";

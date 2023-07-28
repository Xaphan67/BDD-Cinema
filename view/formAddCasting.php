<?php ob_start(); ?>

<div class="main-box-titre bg">
    <h1>Ajouter un acteur au film</h1>
    <p>Merci de remplir tous les champs pour ajouter un acteur.</p>
    <form action="index.php?action=addCasting&id=<?= $idFilm ?>" method="post">
        <div class="form-group">
            <label for="acteur">Acteur *</label>
            <select id="acteur" name="acteur">
                <?php
                foreach ($acteurs->fetchALL() as $acteur) {
                ?>
                    <option value="<?= $acteur["id_acteur"] ?>"><?= $acteur["acteurFilm"] ?></option>
                <?php }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="role">Rôle *</label>
            <select id="role" name="role">
                <?php
                foreach ($roles->fetchALL() as $role) {
                ?>
                    <option value="<?= $role["id_rôle"] ?>"><?= $role["nom_rôle"] ?></option>
                <?php }
                ?>
            </select>
        </div>
        <button type="submit" class="button-submit" name="submit">Ajouter</button>
    </form>
</div>

<?php

$titre = "Ajouter un acteur au film";
$titre_secondaire = "Ajouter un acteur au film";
$contenu = ob_get_clean();

require "view/template.php";

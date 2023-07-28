<?php ob_start();

$infosActeur = $acteur->fetch(); ?>

<div class="main-box-titre bg">
    <h1>Modifier un acteur</h1>
    <p>Merci de remplir tous les champs pour modifier l'acteur.</p>
    <form action="index.php?action=editActeur&id=<?= $infosActeur["id_acteur"] ?>" method="post">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" value="<?= $infosActeur["nom_personne"] ?>">
        </div>
        <div class="form-group">
            <label for="prenom">Prenom *</label>
            <input type="text" id="prenom" name="prenom" value="<?= $infosActeur["prenom_personne"] ?>">
        </div>
        <div class="form-group">
            <label for="sexe">Sexe *</label>
            <select id="sexe" name="sexe">
                <option value="Homme" <?= $infosActeur["sexe_personne"] == "Homme" ? "selected" : "" ?>>Homme</option>
                <option value="Femme" <?= $infosActeur["sexe_personne"] == "Femme" ? "selected" : "" ?>>Femme</option>
            </select>
        </div>
        <div class="form-group">
            <label for="dateNaissance">Date de naissance *</label>
            <input type="date" id="dateNaissance" name="dateNaissance" value="<?= $infosActeur["dateNaissance_personne"] ?>">
        </div>
        <button type="submit" class="button-submit" name="submit">Modifier</button>
    </form>
</div>

<?php

$titre = "Modifier l'acteur";
$titre_secondaire = "Modifier l'acteur";
$contenu = ob_get_clean();

require "view/template.php";
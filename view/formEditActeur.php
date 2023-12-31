<?php ob_start();

$infosActeur = $acteur->fetch(); ?>

<div class="section">
    <h1>Modifier un acteur</h1>
    <p>Merci de remplir tous les champs pour modifier l'acteur</p>
</div>

<div class="article-main">
    <div class="content content-no-bg">
        <form class="form-col" action="index.php?action=editActeur&id=<?= $infosActeur["id_acteur"] ?>" method="post">
            <div class="form-group form-group-col">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= $infosActeur["nom_personne"] ?>" required>
            </div>
            <div class="form-group form-group-col">
                <label for="prenom">Prenom *</label>
                <input type="text" id="prenom" name="prenom" value="<?= $infosActeur["prenom_personne"] ?>" required>
            </div>
            <div class="form-group form-group-col">
                <label for="sexe">Sexe *</label>
                <select id="sexe" name="sexe" required>
                    <option value="">Veuillez sélectionner le sexe</option>
                    <option value="Homme" <?= $infosActeur["sexe_personne"] == "Homme" ? "selected" : "" ?>>Homme</option>
                    <option value="Femme" <?= $infosActeur["sexe_personne"] == "Femme" ? "selected" : "" ?>>Femme</option>
                </select>
            </div>
            <div class="form-group form-group-col">
                <label for="dateNaissance">Date de naissance *</label>
                <input type="date" id="dateNaissance" name="dateNaissance" value="<?= $infosActeur["dateNaissance_personne"] ?>" required>
            </div>
            <button type="submit" class="button-submit form-submit" name="submit">Modifier</button>
        </form>
    </div>
</div>

<?php

$title = "Wiki Films : Modifier un acteur";
$description = "Formulaire d'édition d'un acteur";
$contenu = ob_get_clean();

require "view/template.php";

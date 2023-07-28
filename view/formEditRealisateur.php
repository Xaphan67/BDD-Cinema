<?php ob_start();

$infosRealisateur = $realisateur->fetch(); ?>

<div class="main-box-titre bg">
    <h1>Modifier un rélisateur</h1>
    <p>Merci de remplir tous les champs pour modifier le rélisateur.</p>
    <form action="index.php?action=editRealisateur&id=<?= $infosRealisateur["id_realisateur"] ?>" method="post">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom" value="<?= $infosRealisateur["nom_personne"] ?>">
        </div>
        <div class="form-group">
            <label for="prenom">Prenom *</label>
            <input type="text" id="prenom" name="prenom" value="<?= $infosRealisateur["prenom_personne"] ?>">
        </div>
        <div class="form-group">
            <label for="sexe">Sexe *</label>
            <select id="sexe" name="sexe">
                <option value="Homme" <?= $infosRealisateur["sexe_personne"] == "Homme" ? "selected" : "" ?>>Homme</option>
                <option value="Femme" <?= $infosRealisateur["sexe_personne"] == "Femme" ? "selected" : "" ?>>Femme</option>
            </select>
        </div>
        <div class="form-group">
            <label for="dateNaissance">Date de naissance *</label>
            <input type="date" id="dateNaissance" name="dateNaissance" value="<?= $infosRealisateur["dateNaissance_personne"] ?>">
        </div>
        <button type="submit" class="button-submit" name="submit">Modifier</button>
    </form>
</div>

<?php

$titre = "Modifier le rélisateur";
$titre_secondaire = "Modifier le rélisateur";
$contenu = ob_get_clean();

require "view/template.php";

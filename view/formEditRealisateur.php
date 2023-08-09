<?php ob_start();

$infosRealisateur = $realisateur->fetch(); ?>

<div class="section">
    <h1>Modifier un réalisateur</h1>
    <p>Merci de remplir tous les champs pour modifier le réalisateur</p>
</div>

<article class="article-main">
    <div class="content content-no-bg">
        <form action="index.php?action=editActeur&id=<?= $infosRealisateur["id_realisateur"] ?>" method="post">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" value="<?= $infosRealisateur["nom_personne"] ?>" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prenom *</label>
                <input type="text" id="prenom" name="prenom" value="<?= $infosRealisateur["prenom_personne"] ?>" required>
            </div>
            <div class="form-group">
                <label for="sexe">Sexe *</label>
                <select id="sexe" name="sexe" required>
                    <option value="Homme" <?= $infosRealisateur["sexe_personne"] == "Homme" ? "selected" : "" ?>>Homme</option>
                    <option value="Femme" <?= $infosRealisateur["sexe_personne"] == "Femme" ? "selected" : "" ?>>Femme</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dateNaissance">Date de naissance *</label>
                <input type="date" id="dateNaissance" name="dateNaissance" value="<?= $infosRealisateur["dateNaissance_personne"] ?>" required>
            </div>
            <button type="submit" name="submit">Modifier</button>
        </form>
    </div>
</article>

<?php

$title = "Modifier le rélisateur";
$contenu = ob_get_clean();

require "view/template.php";

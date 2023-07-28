<?php ob_start(); ?>

<div class="main-box-titre bg">
    <h1>Ajouter un rélisateur</h1>
    <p>Merci de remplir tous les champs pour ajouter un rélisateur.</p>
    <form action="index.php?action=addRealisateur" method="post">
        <div class="form-group">
            <label for="nom">Nom *</label>
            <input type="text" id="nom" name="nom">
        </div>
        <div class="form-group">
            <label for="prenom">Prenom *</label>
            <input type="text" id="prenom" name="prenom">
        </div>
        <div class="form-group">
            <label for="sexe">Sexe *</label>
            <select id="sexe" name="sexe">
                <option value="Homme">Homme</option>
                <option value="Femme">Femme</option>
            </select>
        </div>
        <div class="form-group">
            <label for="dateNaissance">Date de naissance *</label>
            <input type="date" id="dateNaissance" name="dateNaissance">
        </div>
        <button type="submit" class="button-submit" name="submit">Ajouter</button>
    </form>
</div>

<?php

$titre = "Ajouter un rélisateur";
$titre_secondaire = "Ajouter un rélisateur";
$contenu = ob_get_clean();

require "view/template.php";

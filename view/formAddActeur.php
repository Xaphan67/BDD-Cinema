<?php ob_start(); ?>

<div class="section">
    <h1>Ajouter un acteur</h1>
    <p>Merci de remplir tous les champs pour ajouter un acteur</p>
</div>

<article class="article-main">
    <div class="content content-no-bg">
        <form action="index.php?action=addActeur" method="post">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prenom *</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="sexe">Sexe *</label>
                <select id="sexe" name="sexe" required>
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dateNaissance">Date de naissance *</label>
                <input type="date" id="dateNaissance" name="dateNaissance" required>
            </div>
            <button type="submit" name="submit">Ajouter</button>
        </form>
    </div>
</article>

<?php

$title = "Ajouter un acteur";
$contenu = ob_get_clean();

require "view/template.php";

<?php ob_start(); ?>

<div class="main-box-titre bg">
    <h1>Ajouter un acteur</h1>
    <p>Merci de remplir tous les champs pour ajouter un acteur.</p>
    <form action="index.php?action=addActeur" method="post">
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
        <div class="form-group">
            <label for="role">Rôle *</label>
            <select id="role" name="role" class="select-right">
                <?php
                foreach ($roles->fetchALL() as $role) {
                ?>
                    <option value="<?= $role["id_rôle"] ?>"><?= $role["nom_rôle"] ?></option>
                <?php }
                ?>
            </select>
            <button type="bouton" class="button-round">
                <a href="index.php?action=formAddRole">
                    +
                </a>
            </button>
        </div>
        <button type="submit" class="button-submit" name="submit">Ajouter</button>
    </form>
</div>

<?php

$titre = "Ajouter un acteur";
$titre_secondaire = "Ajouter un acteur";
$contenu = ob_get_clean();

require "view/template.php";

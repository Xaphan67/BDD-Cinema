<?php ob_start(); ?>

<p></p>Il y a <?= $requete->rowCount() ?> acteurs</p>

<table>
    <thead>
        <tr>
            <th>NOM</th>
            <th>PRENOM</th>
            <th>SEXE</th>
            <th>DATE DE NAISSANCE</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($requete->fetchALL() as $acteur)
            {
                ?>
                <tr>
                    <td><?= $acteur["nom_personne"] ?></td>
                    <td><?= $acteur["prenom_personne"] ?></td>
                    <td><?= $acteur["sexe_personne"] ?></td>
                    <td><?= $acteur["dateNaissance_personne"] ?></td>
                </tr>
            <?php } ?>
    </tbody>
</table>

<?php

$titre = "Liste des acteurs";
$titre_secondaire = "Liste des acteurs";
$contenu = ob_get_clean();

require "view/template.php";
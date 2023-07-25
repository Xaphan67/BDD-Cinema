<?php ob_start(); ?>

<p></p>Il y a <?= $requete->rowCount() ?> réalisateurs</p>

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
            foreach($requete->fetchALL() as $realisateur)
            {
                ?>
                <tr>
                    <td><?= $realisateur["nom_personne"] ?></td>
                    <td><?= $realisateur["prenom_personne"] ?></td>
                    <td><?= $realisateur["sexe_personne"] ?></td>
                    <td><?= $realisateur["dateNaissance_personne"] ?></td>
                </tr>
            <?php } ?>
    </tbody>
</table>

<?php

$titre = "Liste des réalisateurs";
$titre_secondaire = "Liste des réalisateurs";
$contenu = ob_get_clean();

require "view/template.php";
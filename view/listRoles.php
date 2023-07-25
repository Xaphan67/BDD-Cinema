<?php ob_start(); ?>

<p></p>Il y a <?= $requete->rowCount() ?> rôles</p>

<table>
    <thead>
        <tr>
            <th>NOM</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($requete->fetchALL() as $role)
            {
                ?>
                <tr>
                    <td><?= $role["nom_rôle"] ?></td>
                </tr>
            <?php } ?>
    </tbody>
</table>

<?php

$titre = "Liste des rôles";
$titre_secondaire = "Liste des rôles";
$contenu = ob_get_clean();

require "view/template.php";
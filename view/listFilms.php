<?php ob_start(); ?>

<p></p>Il y a <?= $requete->rowCount() ?> films</p>

<table>
    <thead>
        <tr>
            <th>TITRE</th>
            <th>ANNE SORTIE</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($requete->fetchALL() as $film)
            {
                ?>
                <tr>
                    <td><?= $film["titre_film"] ?></td>
                    <td><?= $film["anneeSortie_film"] ?></td>
                </tr>
            <?php } ?>
    </tbody>
</table>

<?php

$titre = "Liste des films";
$titre_secondaire = "Liste des films";
$contenu = ob_get_clean();

require "view/template.php";
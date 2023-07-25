<?php ob_start(); ?>

<p></p>Il y a <?= $requete->rowCount() ?> films</p>

<table>
    <thead>
        <tr>
            <th>TITRE</th>
            <th>ANNEE SORTIE</th>
            <th>DUREE</th>
            <th>AFFICHE</th>
            <th>NOTE</th>
            <th>SYNOPSIS</th>
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
                    <td><?= $film["duree_film"] ?></td>
                    <td><?= $film["affiche_film"] ?></td>
                    <td><?= $film["note_film"] ?></td>
                    <td><?= $film["synopsis_film"] ?></td>
                </tr>
            <?php } ?>
    </tbody>
</table>

<?php

$titre = "Liste des films";
$titre_secondaire = "Liste des films";
$contenu = ob_get_clean();

require "view/template.php";
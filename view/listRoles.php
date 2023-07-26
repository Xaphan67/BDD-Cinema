<?php ob_start();

foreach ($requete->fetchALL() as $role) {
?>
    <div class="genre-role-box bg">
        <h1><a href="index.php?action=infoRole&id=<?= $role["id_rôle"] ?>"><?= $role["nom_rôle"] ?></a></h1>
        <p>Il existe <?= $role["nbActeurs"] ?> acteur<?= $role["nbActeurs"] > 1 ? "s" : "" ?> ayant joué ce role</p>
    </div>
<?php }

$titre = "Liste des rôles";
$titre_secondaire = "Liste des rôles";
$contenu = ob_get_clean();

require "view/template.php";

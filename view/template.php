<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $titre ?></title> <!-- Titre de la page généré dans la vue -->
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <!-- Navigation -->
    <header>
        <div id="banner"></div>
        <nav>
            <a href="index.php">Films</a>
            <a href="index.php?action=listRealisateurs">Réalisateurs</a>
            <a href="index.php?action=listActeurs">Acteurs</a>
            <a href="index.php?action=listGenres">Genres</a>
            <a href="index.php?action=listRoles">Rôles</a>
        </nav>
    </header>
    <main>
        <?= $contenu ?>
    </main>
</body>
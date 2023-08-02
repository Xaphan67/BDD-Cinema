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
        <h1>Wiki Films</h1>
        <input class="hamburger" type="checkbox" id="menu" />
        <label for="menu" aria-label="Menu"><span></span></label>
        <nav>
            <ul>
                <li><a href="index.php">Films</a></li>
                <li><a href="index.php?action=listRealisateurs">Réalisateurs</a></li>
                <li><a href="index.php?action=listActeurs">Acteurs</a></li>
                <li><a href="index.php?action=listGenres">Genres</a></li>
                <li><a href="index.php?action=listRoles">Rôles</a></li>
            </ul>
        </nav>
    </header>
    <div class="section-titre">
        <h1><?= $titre ?></h1>
        <p><a href="index.php?action=<?= $addAction ?>"><?= $addTexte ?></a></p>
    </div>
    <main>
        <?= $contenu ?>
    </main>
</body>
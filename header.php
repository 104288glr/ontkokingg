<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titel ?? 'Home') ?> | Comocedice</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="kop">
    <div class="container kop-inhoud">
        <a href="index.php" class="logo">Comocedice</a>
        <nav class="menu">
            <a href="index.php">Home</a>
            <a href="overzicht.php">Recepten</a>
            <?php if (ingelogd()): ?>
                <span class="gebruiker">Hoi <?= e($_SESSION['username']) ?><?= isAdmin() ? ' <span class="admin-badge">(admin)</span>' : '' ?></span>
                <a href="uitloggen.php">Uitloggen</a>
                <a href="invoer.php" class="knop knop-klein">+ Recept</a>
            <?php else: ?>
                <a href="inloggen.php" class="knop knop-klein">Inloggen</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
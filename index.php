<?php

require 'auth.php';

$nieuwste = $pdo->query('SELECT * FROM recipes ORDER BY created_at DESC LIMIT 6')->fetchAll();


$categorieFotos = [
        'ontbijt' => 'img/havermout-bowl.jpg',
        'lunch' => 'img/panini-caprese.jpg',
        'diner' => 'img/shoarma-bowl.jpg',
        'voorgerecht' => 'img/gazpacho.jpg',
        'hoofdgerecht' => 'img/biefstuk-kappertjes.jpg',
        'nagerecht' => 'img/tiramisu.jpg',
];

$titel = 'Comocedice';
require 'header.php';
?>

    <section class="container hero">
        <div class="hero-tekst">
            <h1>Zelf koken is makkelijker dan je denkt</h1>
            <p>Recepten met gewone boodschappen uit de supermarkt. Zoek op wat je nog in huis hebt.</p>
            <form action="overzicht.php" method="get" class="zoekbalk">
                <input type="text" name="zoek" placeholder="Bijv. pasta, ei, spinazie" aria-label="Zoeken">
                <button type="submit" class="knop">Zoeken</button>
            </form>
        </div>
        <img src="img/rigatoni-chorizo.jpg" alt="Rigatoni met chorizo in een pan" class="hero-foto">
    </section>

    <section class="container blok">
        <h2>Waar heb je zin in?</h2>
        <div class="categorieen">
            <?php foreach ($categorieen as $categorie): ?>
                <a href="overzicht.php?categorie=<?= e($categorie) ?>" class="categorie">
                    <img src="<?= e($categorieFotos[$categorie] ?? '') ?>" alt="">
                    <span><?= e(ucfirst($categorie)) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="container blok">
        <div class="kop-rij">
            <h2>Nieuwste recepten</h2>
            <a href="overzicht.php">Alle recepten bekijken</a>
        </div>
        <div class="raster">
            <?php foreach ($nieuwste as $recept): ?>
                <a href="recept.php?id=<?= $recept['id'] ?>" class="kaart">
                    <?= foto($recept) ?>
                    <div class="kaart-tekst">
                        <span class="label"><?= e(ucfirst($recept['category'])) ?></span>
                        <h3><?= e($recept['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

<?php require 'footer.php'; ?>
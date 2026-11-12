<?php

require 'auth.php';

$categorie = $_GET['categorie'] ?? '';
$zoek = trim($_GET['zoek'] ?? '');

$sql = 'SELECT * FROM recipes WHERE 1=1';
$params = [];

if (in_array($categorie, $categorieen)) {
    $sql .= ' AND category = ?';
    $params[] = $categorie;
} else {
    $categorie = '';
}

if ($zoek !== '') {
    $sql .= ' AND (title LIKE ? OR ingredients LIKE ?)';
    $params[] = "%$zoek%";
    $params[] = "%$zoek%";
}

$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$recepten = $stmt->fetchAll();

$titel = $categorie ? ucfirst($categorie) : 'Recepten';
require 'header.php';
?>

<section class="container blok">
    <h1><?= e($titel) ?></h1>

    <form action="overzicht.php" method="get" class="zoekbalk zoekbalk-klein">
        <?php if ($categorie): ?>
            <input type="hidden" name="categorie" value="<?= e($categorie) ?>">
        <?php endif; ?>
        <input type="text" name="zoek" value="<?= e($zoek) ?>" placeholder="Zoek op naam of ingrediënt" aria-label="Zoeken">
        <button type="submit" class="knop">Zoeken</button>
    </form>

    <div class="filters">
        <a href="overzicht.php" class="filter <?= $categorie === '' ? 'actief' : '' ?>">Alles</a>
        <?php foreach ($categorieen as $cat): ?>
            <a href="overzicht.php?categorie=<?= e($cat) ?>" class="filter <?= $categorie === $cat ? 'actief' : '' ?>"><?= e(ucfirst($cat)) ?></a>
        <?php endforeach; ?>
    </div>

    <?php if (count($recepten) === 0): ?>
        <p class="leeg">Geen recepten gevonden. <a href="invoer.php">Voeg er zelf een toe!</a></p>
    <?php else: ?>
        <div class="raster">
            <?php foreach ($recepten as $recept): ?>
                <a href="recept.php?id=<?= $recept['id'] ?>" class="kaart">
                    <?= foto($recept) ?>
                    <div class="kaart-tekst">
                        <span class="label"><?= e($recept['category']) ?></span>
                        <h3><?= e($recept['title']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require 'footer.php'; ?>

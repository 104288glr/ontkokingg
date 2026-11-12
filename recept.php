<?php

require 'auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM recipes WHERE id = ?');
$stmt->execute([$id]);
$recept = $stmt->fetch();

$titel = $recept ? $recept['title'] : 'Niet gevonden';
require 'header.php';
?>

<section class="container blok">
    <?php if (!$recept): ?>
        <h1>Recept niet gevonden</h1>
        <p><a href="overzicht.php">Terug naar alle recepten</a></p>
    <?php else: ?>
        <div class="detail">
            <?= foto($recept, 'detail-foto') ?>
            <div>
                <span class="label"><?= e($recept['category']) ?></span>
                <h1><?= e($recept['title']) ?></h1>

                <h2>Ingrediënten</h2>
                <ul class="ingredienten">
                    <?php foreach (regels($recept['ingredients']) as $regel): ?>
                        <li><?= e($regel) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <h2>Zo maak je het</h2>
        <ol class="stappen">
            <?php foreach (regels($recept['instructions']) as $regel): ?>
                <li><?= e($regel) ?></li>
            <?php endforeach; ?>
        </ol>

        <?php if (magBewerken($recept)): ?>

            <div class="acties">
                <a href="invoer.php?id=<?= $recept['id'] ?>" class="knop">Aanpassen</a>
                <form action="verwijderen.php" method="post" onsubmit="return confirm('Weet je zeker dat je dit recept wilt verwijderen?');">
                    <input type="hidden" name="id" value="<?= $recept['id'] ?>">
                    <button type="submit" class="knop knop-rood">Verwijderen</button>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php require 'footer.php'; ?>

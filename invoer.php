<?php
require 'auth.php';
moetInloggen();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$recept = ['title' => '', 'category' => '', 'ingredients' => '', 'instructions' => '', 'image' => '', 'user_id' => $_SESSION['user_id']];
$fouten = [];

// Bij aanpassen: recept ophalen en checken of het van jou is
if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM recipes WHERE id = ?');
    $stmt->execute([$id]);
    $bestaand = $stmt->fetch();

    if (!$bestaand || !magBewerken($bestaand)) {
        header('Location: overzicht.php');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $recept = $bestaand;
    }
}


$wachten = $id === 0 ? wachttijd($pdo) : 0;


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $wachten === 0) {
    $recept = [
        'title' => trim($_POST['title'] ?? ''),
        'category' => $_POST['category'] ?? '',
        'ingredients' => trim($_POST['ingredients'] ?? ''),
        'instructions' => trim($_POST['instructions'] ?? ''),
        'image' => trim($_POST['image'] ?? ''),
    ];

    if ($recept['title'] === '') $fouten[] = 'Vul een naam in.';
    if (!in_array($recept['category'], $categorieen)) $fouten[] = 'Kies een categorie.';
    if ($recept['ingredients'] === '') $fouten[] = 'Vul de ingrediënten in.';
    if ($recept['instructions'] === '') $fouten[] = 'Vul de stappen in.';

    if (count($fouten) === 0) {
        $waarden = [$recept['title'], $recept['category'], $recept['ingredients'], $recept['instructions'], $recept['image'] ?: null];

        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE recipes SET title = ?, category = ?, ingredients = ?, instructions = ?, image = ? WHERE id = ?');
            $stmt->execute(array_merge($waarden, [$id]));
        } else {

            $stmt = $pdo->prepare('INSERT INTO recipes (title, category, ingredients, instructions, image, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute(array_merge($waarden, [$_SESSION['user_id'], date('Y-m-d H:i:s')]));
            $id = (int)$pdo->lastInsertId();
        }

        header("Location: recept.php?id=$id");
        exit;
    }
}

$titel = $id > 0 ? 'Recept aanpassen' : 'Recept toevoegen';
require 'header.php';
?>

<section class="container blok smal">
    <h1><?= e($titel) ?></h1>

    <?php if ($wachten > 0): ?>
        <?php
        $totaalMinuten = (int)ceil($wachten / 60);
        $uren = intdiv($totaalMinuten, 60);
        $minuten = $totaalMinuten % 60;
        ?>
        <div class="infomelding">
            <p><strong>Je hebt vandaag al een recept geplaatst.</strong></p>
            <p>Je kunt over <?= $uren ?> uur en <?= $minuten ?> minuten weer een nieuw recept toevoegen.</p>
        </div>
        <p><a href="overzicht.php" class="knop">Bekijk recepten</a></p>
    <?php else: ?>

        <?php if ($id === 0): ?>
            <p class="intro">Je kunt 1 recept per 24 uur plaatsen. Maak er een goede van!</p>
        <?php endif; ?>

        <?php if ($fouten): ?>
            <div class="foutmelding">
                <?php foreach ($fouten as $fout): ?>
                    <p><?= e($fout) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="formulier">
            <input type="hidden" name="id" value="<?= $id ?>">

            <label for="title">Naam van het gerecht</label>
            <input type="text" id="title" name="title" maxlength="100" value="<?= e($recept['title']) ?>" required>

            <label for="category">Categorie</label>
            <select id="category" name="category" required>
                <option value="">Kies een categorie</option>
                <?php foreach ($categorieen as $cat): ?>
                    <option value="<?= e($cat) ?>" <?= $recept['category'] === $cat ? 'selected' : '' ?>><?= e(ucfirst($cat)) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="ingredients">Ingrediënten <small>(één per regel)</small></label>
            <textarea id="ingredients" name="ingredients" rows="6" required><?= e($recept['ingredients']) ?></textarea>

            <label for="instructions">Stappen <small>(één stap per regel)</small></label>
            <textarea id="instructions" name="instructions" rows="6" required><?= e($recept['instructions']) ?></textarea>

            <label for="image">Foto <small>(link of bijv. img/naam.jpg, mag leeg)</small></label>
            <input type="text" id="image" name="image" value="<?= e($recept['image']) ?>" placeholder="https://... of img/foto.jpg">

            <button type="submit" class="knop">Opslaan</button>
        </form>
    <?php endif; ?>
</section>

<?php require 'footer.php'; ?>

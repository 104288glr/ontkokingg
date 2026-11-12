<?php
require 'auth.php';
moetInloggen();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);

    $stmt = $pdo->prepare('SELECT * FROM recipes WHERE id = ?');
    $stmt->execute([$id]);
    $recept = $stmt->fetch();

    if ($recept && magBewerken($recept)) {
        $stmt = $pdo->prepare('DELETE FROM recipes WHERE id = ?');
        $stmt->execute([$id]);
    }
}

header('Location: overzicht.php');
exit;

<?php

require 'auth.php';

if (ingelogd()) {
    header('Location: index.php');
    exit;
}

$gebruikersnaam = '';
$fout = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['username'] ?? '');
    $wachtwoord = $_POST['password'] ?? '';


    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$gebruikersnaam, $gebruikersnaam]);
    $gebruiker = $stmt->fetch();

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$gebruiker['id'];
        $_SESSION['username'] = $gebruiker['username'];
        $_SESSION['role'] = $gebruiker['role'];
        header('Location: index.php');
        exit;
    }

    $fout = 'Gebruikersnaam of wachtwoord klopt niet.';
}

$titel = 'Inloggen';
require 'header.php';
?>

<section class="container blok smal">
    <h1>Inloggen</h1>

    <?php if (($_GET['melding'] ?? '') === 'inloggen'): ?>
        <div class="infomelding"><p>Log eerst in om recepten toe te voegen of aan te passen.</p></div>
    <?php endif; ?>

    <?php if ($fout): ?>
        <div class="foutmelding"><p><?= e($fout) ?></p></div>
    <?php endif; ?>

    <form method="post" class="formulier">
        <label for="username">Gebruikersnaam of e-mailadres</label>
        <input type="text" id="username" name="username" value="<?= e($gebruikersnaam) ?>" required>

        <label for="password">Wachtwoord</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="knop">Inloggen</button>
    </form>

    <p class="onder-formulier">Nog geen account? <a href="registreren.php">Maak er een aan</a></p>
</section>

<?php require 'footer.php'; ?>

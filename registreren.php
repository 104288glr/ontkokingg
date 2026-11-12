<?php
require 'auth.php';
if (ingelogd()) { header('Location: index.php'); exit; }

$gebruikersnaam = '';
$email = '';
$fouten = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikersnaam = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['password'] ?? '';

    if (strlen($gebruikersnaam) < 3) $fouten[] = 'Je gebruikersnaam moet minstens 3 tekens zijn.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $fouten[] = 'Vul een geldig e-mailadres in.';
    if (strlen($wachtwoord) < 6) $fouten[] = 'Je wachtwoord moet minstens 6 tekens zijn.';
    if ($wachtwoord !== ($_POST['password2'] ?? '')) $fouten[] = 'De wachtwoorden zijn niet hetzelfde.';

    if (!$fouten) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$gebruikersnaam, $email]);
        if ($stmt->fetch()) $fouten[] = 'Deze gebruikersnaam of dit e-mailadres is al in gebruik.';
    }

    if (!$fouten) {
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$gebruikersnaam, $email, password_hash($wachtwoord, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        $_SESSION['username'] = $gebruikersnaam;
        $_SESSION['role'] = 'user';
        header('Location: index.php');
        exit;
    }
}

$titel = 'Account maken';
require 'header.php';
?>
<section class="container blok smal">
    <h1>Account maken</h1>
    <?php if ($fouten): ?>
        <div class="foutmelding">
            <?php foreach ($fouten as $fout): ?><p><?= e($fout) ?></p><?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" class="formulier">
        <label for="username">Gebruikersnaam</label>
        <input type="text" id="username" name="username" maxlength="50" value="<?= e($gebruikersnaam) ?>" required>
        <label for="email">E-mailadres</label>
        <input type="email" id="email" name="email" maxlength="100" value="<?= e($email) ?>" required>
        <label for="password">Wachtwoord <small>(minstens 6 tekens)</small></label>
        <input type="password" id="password" name="password" required>
        <label for="password2">Herhaal wachtwoord</label>
        <input type="password" id="password2" name="password2" required>
        <button type="submit" class="knop">Account maken</button>
    </form>
    <p class="onder-formulier">Heb je al een account? <a href="inloggen.php">Log hier in</a></p>
</section>
<?php require 'footer.php'; ?>

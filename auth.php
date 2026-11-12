<?php
session_start();
require 'db.php';

function ingelogd()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return ($_SESSION['role'] ?? '') === 'admin';
}

function moetInloggen()
{
    if (!ingelogd()) {
        header('Location: inloggen.php?melding=inloggen');
        exit;
    }
}


function magBewerken($recept)
{
    if (!ingelogd()) return false;
    if (isAdmin()) return true;
    return (int)($recept['user_id'] ?? 0) === (int)$_SESSION['user_id'];
}


function wachttijd($pdo)
{
    if (isAdmin()) return 0;
    $stmt = $pdo->prepare('SELECT MAX(created_at) FROM recipes WHERE user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $laatste = $stmt->fetchColumn();
    if (!$laatste) return 0;
    return max(0, strtotime($laatste) + 86400 - time());
}

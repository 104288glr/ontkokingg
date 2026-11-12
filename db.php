<?php

$host = '127.0.0.1';
$dbnaam = '104288_beroeps';
$gebruiker = '104288ftp';
$wachtwoord = '3EN966FKMJ';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbnaam;charset=utf8mb4", $gebruiker, $wachtwoord);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $fout) {
    die('Geen verbinding met de database: ' . $fout->getMessage());
}


$categorieen = ['ontbijt', 'lunch', 'diner', 'voorgerecht', 'hoofdgerecht', 'nagerecht'];


function e($tekst)
{
    return htmlspecialchars($tekst ?? '', ENT_QUOTES, 'UTF-8');
}


function regels($tekst)
{
    return array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $tekst ?? '')));
}


function foto($recept, $klasse = 'kaart-foto')
{
    if (!empty($recept['image'])) {
        return '<img class="' . $klasse . '" src="' . e($recept['image']) . '" alt="' . e($recept['title']) . '">';
    }
    $letter = mb_strtoupper(mb_substr($recept['title'], 0, 1));
    return '<div class="' . $klasse . ' geen-foto">' . e($letter) . '</div>';
}

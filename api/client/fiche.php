<?php

// Code d'exemple
// N'hésitez pas à vous en inspirer !

header('Access-Control-Allow-Origin: *'); // Histoire de pouvoir accéder à l'API depuis autre part que le serveur
header('Content-type: application/json'); // Histoire de faire comprendre au client que c'est du JSON

$droit = 0;
$login = "undefined";

if (isset($_POST['jeton'])) {
    // Note : Ceci devrait être mis dans une fonction (avec des vrais tokens qui
    // seront générés lors de l'appel à api/login) mais vu que c'est un exemple
    // j'ai la flemme.
    // D'ailleurs, pendant le développement puisque la phase de login est
    // probablement difficile à implémenter, on pourra utiliser des tokens
    // godmode (genre, '0', '1' ,'2' et '3') qui fonctionnent à tous les
    // coups ^^
    if ($_POST['jeton'] == 'ahcheesinaib3eedaeshep7fooShee') {
    // Si le jeton appartient à un membre du BDE
        $droit = 1;
        $login = "bdeman";
    } else if ($_POST['jeton'] == 'cuQu1vahghu8UK2woozooghu1aot4n') {
    // Si le jeton appartient à un membre du bar
        $droit = 2;
        $login = "barman";
    } else if ($_POST['jeton'] == 'Phohhu3eengeingae8kab3weif3neb') {
    // Si le jeton appartient au prez
        $droit = 3;
        $login = "theprez";
    } else {
    // Si le jeton est erroné, ou a expiré (dans l'exemples ils n'expirent pas ^^)
    ?>
{
    "status": "jeton_errone"
}
<?php
        exit();
    }
} else {
// Si pas de jeton
?>
{
	"status": "jeton_vide"
}
<?php
    exit();
}

if ($droit < 2) {
?>
{
	"status": "non_autorise"
}
<?php
    exit();
}

function loginLille1Valide($login) {
    // Vérifie si le login est correct
    return true; // :p
}

if (isset($_POST['loginLille1']) && loginLille1Valide($_POST['loginLille1'])) {
    $loginLille1 = $_POST['loginLille1'];
} else {
?>
{
	"status": "requete_malformee"
}
<?php
    exit();
}

// On checke si le loginLille1 est connu dans la base de données
if ($loginLille1 != 'petite.jaja') {
?>
{
	"status": "etudiant_inconnu"
}
<?php
    exit();
}

?>
{
	"status": "ok",
	"loginLille1": "petite.jaja",
	"idCarteEtudiant": "AHS0DIEX",
	"solde": 48.3,
<?php
if ($droit >= 3) {
?>
	"decouvertAutorise": false,
<?php
}
?>
	"transactions": [{
		"id": 5,
		"type": 1,
		"date": 1460369884183,
		"montant": 50,
		"qte": 0,
		"valide": true
	}, {
		"id": 6,
		"type": 3,
		"date": 1460370161326,
		"montant": 1.7,
		"qte": 1,
		"valide": true
	}]
}

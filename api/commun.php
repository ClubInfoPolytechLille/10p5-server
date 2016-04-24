<?php

include_once("constantes.php");

// Définition des headers HTTP
header('Access-Control-Allow-Origin: *'); // Histoire de pouvoir accéder à l'API depuis autre part que le serveur
header('Content-type: application/json'); // Histoire de faire comprendre au client que c'est du JSON

// Définition des constantes

define("JETON_TAILLE", 30); // Taille d'un jeton
define("JETON_DUREE", 10*60); // Temps de validité du jeton en secondes

// Fonctions utiles
function retour($status, $donnees = array()) { // Renvoie les données passées
    $donnees['status'] = $status;
    echo json_encode($donnees)."\r\n";
    global $db;
    if (isset($db) && $db) {
        $db->close();
    }
    exit();
}

function donne($parametre) { // Vérifie si le paramètre est donné
    if (isset($_POST[$parametre]) && $_POST[$parametre]) {
        return $_POST[$parametre];
    } else {
        return false;
    }
}

mysqli_report(MYSQLI_REPORT_ALL);

// Connexion à la base de donnée
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    retour("erreur_bdd", ["message" => $db->connect_error]);
}

// Vérification de la présence de bcrypt
if (!defined("CRYPT_BLOWFISH") || !CRYPT_BLOWFISH) {
    retour("manque_bcrypt");
}


?>

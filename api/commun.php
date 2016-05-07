<?php

include_once("constantes.php");

// Définition des headers HTTP
header('Access-Control-Allow-Origin: *'); // Histoire de pouvoir accéder à l'API depuis autre part que le serveur
header('Content-type: application/json'); // Histoire de faire comprendre au client que c'est du JSON

// Définition des constantes

define("JETON_TAILLE", 30); // Taille d'un jeton
define("JETON_DUREE", 5*60); // Temps de validité du jeton en secondes

define("TRANSACTION_CREATION", 1);
define("TRANSACTION_RECHARGEMENT", 2);
define("TRANSACTION_PAIEMENT", 3);
define("TRANSACTION_VIDANGE", 4);

define("TRANSACTION_DUREE", 120);

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

function supprimerJeton($jeton) {
    global $db;
    $requete = $db->prepare("DELETE FROM Sessions WHERE jeton=?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("s", $jeton);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    $requete->close();
}

function verifierJeton($jeton) {
    global $db, $login, $droit;
    $requete = $db->prepare("SELECT Utilisateurs.login, Utilisateurs.droit, UNIX_TIMESTAMP(Sessions.date) FROM Utilisateurs JOIN Sessions ON Utilisateurs.login=Sessions.utilisateur WHERE Sessions.jeton=?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("s", $jeton);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    // On écrit dans les variables globales $login & $droit
    $requete->bind_result($login, $droit, $date);
    if ($requete->fetch()) {
        if (time() > $date + JETON_DUREE) {
            retour("jeton_expire");
        }
    } else {
        retour("jeton_invalide");
    }
    $requete->close();

    // Augmentation du timer
    $requete = $db->prepare("UPDATE Sessions SET date=CURRENT_TIME WHERE jeton=?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("s", $jeton);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    $requete->close();
}

function verifierDroit($droitMinimum, $retour = "droits_insuffisants") {
    global $droit;
    if (donne("jeton")) {
        verifierJeton(donne("jeton"));
        if ($droit < $droitMinimum) {
            retour($retour);
        }
    } else {
        retour("jeton_vide");
    }
}

function utilisateurExiste($login) {
    global $db;
    $requete = $db->prepare("SELECT login FROM Utilisateurs WHERE login=?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("s", $login);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    return $requete->fetch();
    $requete->close();
}

function clientExiste($idCarte) {
    global $db;
    $requete = $db->prepare("SELECT idCarte FROM Clients WHERE idCarte=?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("s", $idCarte);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    return $requete->fetch();
    $requete->close();
}

function transaction($type, $client, $montant = 0, $quantite = 0) {
    global $db, $login;
    $requete = $db->prepare("INSERT INTO Transactions (type, client, montant, quantite, utilisateur) VALUES (?, ?, ?, ?, ?)");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("sssss", $type, $client, $montant, $quantite, $login);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    $requete->close();
    return $db->insert_id;
}

// Variables globales

$login = "";
$droit = 0;

// Connexion à la base de donnée
$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($db->connect_error) {
    retour("erreur_bdd", ["message" => $db->connect_error]);
}


?>

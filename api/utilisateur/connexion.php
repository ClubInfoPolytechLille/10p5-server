<?php

require_once("../commun.php");

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    // Depuis http://stackoverflow.com/a/31107425/2766106
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[rand(0, $max)];
    }
    return $str;
}

// Vérification des paramètres

if (donne("login") && donne("mdp")) {
    // Si l'utilisateur s'authentifie par login + mdp
    $login = donne("login");
    $requete = $db->prepare("SELECT mdp FROM Utilisateurs WHERE login = ?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("s", $login);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    $requete->bind_result($mdpHash);
    if ($requete->fetch()) {
        if (!password_verify(donne("mdp"), $mdpHash)) {
            retour("identifiants_invalides"); // Mot de passe incorrect
        }
    } else {
        retour("identifiants_invalides"); // Identifiant inconnu
    }
    $requete->close();

} else if (donne("idCarte")) {
    $requete = $db->prepare("SELECT login FROM Utilisateurs WHERE idCarte = ?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $idCarte = donne("idCarte");
    $requete->bind_param("s", $idCarte);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    $requete->bind_result($login);
    if (!$requete->fetch()) {
        retour("carte_inconnue");
    }
    $requete->close();

} else {
    retour("requete_malformee");
}

// Ajout du jeton dans la base de données
$jeton = random_str(JETON_TAILLE);

$requete = $db->prepare("INSERT INTO Sessions (jeton, utilisateur) VALUES (?, ?)");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("ss", $jeton, $login);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

// Récupération des données de l'utilisateur

$requete = $db->prepare("SELECT droit FROM Utilisateurs WHERE login = ?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $login);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($droit);
$requete->fetch();
$requete->close();

retour("ok", ["jeton" => $jeton, "login" => $login, "droit" => $droit]);

?>

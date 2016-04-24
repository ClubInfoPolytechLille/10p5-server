<?php

require_once("commun.php");

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    // Depuis http://stackoverflow.com/a/31107425/2766106
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

// Vérification des paramètres

if (donne("login") && donne("mdp")) {
    // Si l'utilisateur s'authentifie par login + mdp
    $login = donne("login");
    $requete = $db->prepare("SELECT mdp FROM Utilisateurs WHERE login = ?");
    $requete->bind_param("s", $login);
    $requete->execute();
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
    // Si l'utilisateur s'authentifie par carte
    // $login = ...
    retour("non_implemente"); // TODO
} else {
    retour("requete_malformee");
}

// Ajout du jeton dans la base de données
$jeton = random_str(JETON_TAILLE);

$requete = $db->prepare("INSERT INTO Sessions (jeton, utilisateur) VALUES (?, ?)");
$requete->bind_param("ss", $jeton, $login);
$requete->execute();
$requete->close();

// Récupération des données de l'utilisateur

$requete = $db->prepare("SELECT droit FROM Utilisateurs WHERE login = ?");
$requete->bind_param("s", $login);
$requete->execute();
$requete->bind_result($droit);
$requete->fetch();
$requete->close();

retour("ok", ["jeton" => $jeton, "login" => $login, "droit" => $droit]);

?>

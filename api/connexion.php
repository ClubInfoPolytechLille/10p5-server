<?php

require_once("commun.php");

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
} else if (donne("idCarte")) {
    // Si l'utilisateur s'authentifie par carte
    retour("non_implemente"); // TODO
} else {
    retour("requete_malformee");
}

retour("ok");

?>

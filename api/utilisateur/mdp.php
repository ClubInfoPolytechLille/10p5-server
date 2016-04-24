<?php

require_once("../commun.php");

verifierJeton(donne("jeton"));

if (!(donne("login") && donne("mdp"))) {
    retour("requete_malformee");
}

if (!utilisateurExiste(donne("login"))) {
    retour("utilisateur_inconnu");
}

if ($login != donne("login")) {
    // Si on essaye de modifier le mot de passe de quelqu'un d'autre
    // on doit être le président
    verifierDroit(3);
}

$requete = $db->prepare("UPDATE Utilisateurs SET mdp=? WHERE login=?");
$mdpHash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
$requete->bind_param("ss", $mdpHash, $_POST["login"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

retour("ok");

?>

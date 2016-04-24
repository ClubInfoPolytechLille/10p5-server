<?php

require_once("../commun.php");

verifierDroit(3);

if (!(donne("login") && donne("mdp") && donne("droit"))) {
    retour("requete_malformee");
}

if (utilisateurExiste(donne("login"))) {
    retour("utilisateur_existant");
}

$requete = $db->prepare("INSERT INTO Utilisateurs (login, mdp, droit) VALUES (?, ?, ?)");
$mdpHash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
$requete->bind_param("sss", $_POST["login"], $mdpHash, $_POST["droit"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

// TODO loginLille1 && idCarte

retour("ok");

?>

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
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$mdpHash = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
$requete->bind_param("sss", $_POST["login"], $mdpHash, $_POST["droit"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

if (donne("idCarte")) {
    $requete = $db->prepare("UPDATE Utilisateurs SET idCarte=? WHERE login=?");
    if (!$requete) {
        retour("erreur_bdd_preparee", ["message" => $db->error]);
    }
    $requete->bind_param("ss", $_POST["idCarte"], $_POST["login"]);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    $requete->close();
}

retour("ok");

?>

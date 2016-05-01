<?php

require_once("../commun.php");

verifierDroit(1);

if (!donne("idCarte")) {
    retour("requete_malformee");
}

// Vérfie si un utilisateur possède cette carte
$requete = $db->prepare("SELECT login FROM Utilisateurs WHERE idCarte=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $_POST['idCarte']);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$utilisateur = false;
$requete->bind_result($utilisateur);
$requete->fetch();
$requete->close();

// Vérfie si un client possède cette carte
$requete = $db->prepare("SELECT idCarte FROM Clients WHERE idCarte=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $_POST['idCarte']);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$client = false;
$requete->bind_result($client);
$requete->fetch();
$requete->close();

retour("ok", ["utilisateur" => $utilisateur, "client" => $client]);

?>

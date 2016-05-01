<?php

require_once("../commun.php");

verifierJeton(donne("jeton"));

if (!donne("login")) {
    retour("requete_malformee");
}

if ($login != donne("login")) {
    verifierDroit(3);
}

// Informations sur l'utilisateur
$requete = $db->prepare("SELECT idCarte, droit FROM Utilisateurs WHERE login=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $_POST["login"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($idCarte, $droit);
if (!$requete->fetch()) {
    retour("utilisateur_inconnu");
}
$requete->close();

// Transactions de l'utilisateur
$requete = $db->prepare("SELECT id, type, client, UNIX_TIMESTAMP(date), montant, quantite, valide FROM Transactions WHERE utilisateur=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $_POST["login"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($id, $type, $client, $date, $montant, $quantite, $valide);
$transactions = [];
while($requete->fetch()) {
    $transaction = ["id" => $id, "type" => $type, "client" => $client, "date" => $date, "montant" => $montant, "quantite" => $quantite, "utilisateur" => $_POST["login"], "valide" => $valide];
    $transactions[] = $transaction;

}
$requete->close();


retour("ok", ["login" => $_POST["login"], "idCarte" => $idCarte, "droit" => $droit, "transactions" => $transactions]);

?>

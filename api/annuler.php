<?php

require_once("commun.php");

verifierJeton(donne("jeton"));

if (!donne("idTransaction")) {
    retour("requete_malformee");
}

$requete = $db->prepare("SELECT type, client, UNIX_TIMESTAMP(date), montant, utilisateur, valide FROM Transactions WHERE id=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $_POST['idTransaction']);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($type, $client, $date, $montant, $utilisateur, $valide);
if (!$requete->fetch()) {
    retour("transaction_inconnue");
}
$requete->close();

if (!$valide) {
    retour("transaction_deja_annulee");
}

if ($utilisateur != $login) {
    verifierDroit(3, "transaction_autre");
}

if (time() > $date + TRANSACTION_DUREE) {
    verifierDroit(3, "transaction_expire");
}

$requete = $db->prepare("SELECT solde FROM Clients WHERE idCarte=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $client);
$requete->bind_result($soldeAncien);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->fetch();
$requete->close();

switch ($type) {
case TRANSACTION_CREATION:
case TRANSACTION_RECHARGEMENT:
    $soldeNouveau = $soldeAncien - $montant;
    break;

case TRANSACTION_PAIEMENT:
case TRANSACTION_VIDANGE:
    $soldeNouveau = $soldeAncien + $montant;
    break;

default:
    retour("erreur_script");
    break;
}

$requete = $db->prepare("UPDATE Clients SET solde=? WHERE idCarte=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("ss", $soldeNouveau, $client);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();


$requete = $db->prepare("UPDATE Transactions SET valide=0 WHERE id=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $_POST["idTransaction"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

retour("ok", ["client" => $client, "soldeAncien" => $soldeAncien, "soldeNouveau" => $soldeNouveau, "idTransaction" => $_POST['idTransaction']]);

?>

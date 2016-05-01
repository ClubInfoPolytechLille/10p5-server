<?php

require_once("../commun.php");

verifierDroit(2);

if (!(donne("idCarte") && donne("montant"))) {
    retour("requete_malformee");
}

if (!clientExiste(donne("idCarte"))) {
    retour("client_inconnu");
}

$montant = floatval($_POST["montant"]);

if ($montant <= 0) {
    retour("rechargement_negatif");
}

$requete = $db->prepare("SELECT solde FROM Clients WHERE idCarte=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("s", $_POST["idCarte"]);
$requete->bind_result($soldeAncien);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->fetch();
$requete->close();

$soldeNouveau = $soldeAncien + $montant;

$requete = $db->prepare("UPDATE Clients SET solde=? WHERE idCarte=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("ss", $soldeNouveau, $_POST["idCarte"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();
$idTransaction = transaction(TRANSACTION_RECHARGEMENT, $_POST["idCarte"], $montant);


retour("ok", ["idTransaction" => $idTransaction, "soldeAncien" => $soldeAncien, "soldeNouveau" => $soldeNouveau]);

?>

<?php

require_once("../commun.php");

verifierJeton(donne("jeton"));

if (!donne("idCarte")) {
    retour("requete_malformee");
}

// Informations sur l'utilisateur
$requete = $db->prepare("SELECT decouvert, solde FROM Clients WHERE idCarte=?");
$requete->bind_param("s", $_POST["idCarte"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($decouvert, $solde);
if (!$requete->fetch()) {
    retour("client_inconnu");
}
$requete->close();

// Transactions de l'utilisateur
$requete = $db->prepare("SELECT id, type, UNIX_TIMESTAMP(date), montant, quantite, utilisateur, valide FROM Transactions WHERE client=?");
$requete->bind_param("s", $_POST["idCarte"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($id, $type, $date, $montant, $quantite, $utilisateur, $valide);
$transactions = [];
while($requete->fetch()) {
    $transaction = ["id" => $id, "type" => $type, "client" => $_POST["idCarte"], "date" => $date, "montant" => $montant, "quantite" => $quantite, "utilisateur" => $utilisateur, "valide" => $valide];
    $transactions[] = $transaction;

}
$requete->close();


$donnes = ["idCarte" => $_POST["idCarte"], "solde" => $solde, "transactions" => $transactions];
if ($droit >= 3) {
    $donnes["decouvert"] = !!$decouvert;
}

retour("ok", $donnes);

?>

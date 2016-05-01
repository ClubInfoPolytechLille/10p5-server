<?php

require_once("commun.php");

verifierDroit(3);

$requete = $db->prepare("SELECT id, type, client, UNIX_TIMESTAMP(date), montant, quantite, utilisateur, valide FROM Transactions");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($id, $type, $client, $date, $montant, $quantite, $utilisateur, $valide);
$transactions = [];
while($requete->fetch()) {
    $transaction = ["id" => $id, "type" => $type, "client" => $client, "date" => $date, "montant" => $montant, "quantite" => $quantite, "utilisateur" => $utilisateur, "valide" => $valide];
    $transactions[] = $transaction;

}
$requete->close();


retour("ok", ["transactions" => $transactions]);

?>

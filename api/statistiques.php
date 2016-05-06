<?php

require_once("commun.php");

verifierDroit(2);

// Chiffre d'affaire
$requete = $db->prepare("SELECT SUM(solde) FROM Clients WHERE solde > 0");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($recettes);
$requete->fetch();
$requete->close();

$requete = $db->prepare("SELECT SUM(solde) FROM Clients WHERE solde < 0");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($dettes);
$requete->fetch();
$requete->close();

// Arrondi et calculs
$recettes = round($recettes, 2);
$dettes = round($dettes, 2);
$benefices = $recettes + $dettes;

// Statistiques générales
$requete = $db->prepare("SELECT COUNT(*) FROM Clients");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($clientsNb);
$requete->fetch();
$requete->close();

$requete = $db->prepare("SELECT SUM(quantite) FROM Transactions");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($consommationsNb);
$requete->fetch();
$requete->close();

$requete = $db->prepare("SELECT AVG(solde) FROM Clients WHERE solde > 0");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($soldeMoy);
$requete->fetch();
$requete->close();

// Facture BDE

// TODO Même si le BDE n'est censé que pouvoir faire des créations de compte,
// utiliser la fonction de somme de transactions utilisée pour vérifier
// l'intégrité des soldes pourrait être une bonne idée
$requete = $db->prepare("SELECT SUM(Transactions.montant) FROM Transactions JOIN Utilisateurs ON Transactions.utilisateur=Utilisateurs.login WHERE droit=1");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($factureBDE);
$requete->fetch();


retour("ok", [
    "benefices" => $benefices,
    "recettes" => $recettes,
    "dettes" => $dettes,
    "clientsNb" => $clientsNb,
    "consommationsNb" => $consommationsNb,
    "soldeMoy" => $soldeMoy,
    "factureBDE" => $factureBDE,
]);

?>

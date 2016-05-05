<?php

require_once("../commun.php");

verifierDroit(1);

if (!(donne("idCarte") && donne("solde"))) {
    retour("requete_malformee");
}

if (clientExiste(donne("idCarte"))) {
    retour("client_existant");
}

if (donne("decouvert") && $_POST["decouvert"] != "false" && $_POST["decouvert"] != "0") {
    verifierDroit(3);
    $decouvert = 1;
} else {
    $decouvert = 0;
}

$solde = floatval($_POST["solde"]);

if ($solde < 0 && !$decouvert) {
    retour("solde_negatif");
}


$requete = $db->prepare("INSERT INTO Clients (idCarte, solde, decouvert) VALUES (?, ?, ?)");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("sss", $_POST["idCarte"], $solde, $decouvert);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();
$idTransaction = transaction(TRANSACTION_CREATION, $_POST["idCarte"], $solde);


retour("ok", ["idTransaction" => $idTransaction, "soldeNouveau" => $solde]);

?>

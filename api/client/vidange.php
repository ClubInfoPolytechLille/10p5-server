<?php

require_once("../commun.php");

verifierDroit(2);

if (!(donne("idCarte"))) {
    retour("requete_malformee");
}

if (!clientExiste(donne("idCarte"))) {
    retour("client_inconnu");
}

$requete = $db->prepare("SELECT solde FROM Clients WHERE idCarte=?");
$requete->bind_param("s", $_POST["idCarte"]);
$requete->bind_result($soldeAncien);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->fetch();
$requete->close();

if ($soldeAncien <= 0) {
    retour("solde_negatif");
}

$requete = $db->prepare("UPDATE Clients SET solde=0 WHERE idCarte=?");
$requete->bind_param("s", $_POST["idCarte"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();
$idTransaction = transaction(TRANSACTION_VIDANGE, $_POST["idCarte"], $soldeAncien);


retour("ok", ["idTransaction" => $idTransaction, "soldeAncien" => $soldeAncien]);

?>

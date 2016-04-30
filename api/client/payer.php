<?php

require_once("../commun.php");

verifierDroit(2);

if (!(donne("idCarte") && (donne("montant") XOR donne("quantite")))) {
    retour("requete_malformee");
}

if (!clientExiste(donne("idCarte"))) {
    retour("client_inconnu");
}

if (donne("quantite")) {
    $quantite = intval($_POST["quantite"]);
    $requete = $db->prepare("SELECT prix FROM Prix");
    $requete->bind_result($prixItem);
    if (!$requete->execute()) {
        retour("erreur_bdd", ["message" => $requete->error]);
    }
    $requete->fetch();
    $requete->close();

    $montant = $prixItem * $quantite;
} else {
    $montant = floatval($_POST["montant"]);
    $quantite = 0;
}

if ($montant <= 0) {
    retour("paiement_negatif");
}

$requete = $db->prepare("SELECT solde, decouvert FROM Clients WHERE idCarte=?");
$requete->bind_param("s", $_POST["idCarte"]);
$requete->bind_result($soldeAncien, $decouvert);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->fetch();
$requete->close();

$soldeNouveau = $soldeAncien - $montant;

if ($soldeNouveau < 0 && !$decouvert) {
    retour("solde_insuffisant", ["solde" => $soldeAncien, "manque" => abs($soldeNouveau)]);
}

$requete = $db->prepare("UPDATE Clients SET solde=? WHERE idCarte=?");
$requete->bind_param("ss", $soldeNouveau, $_POST["idCarte"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();
$idTransaction = transaction(TRANSACTION_PAIEMENT, $_POST["idCarte"], $montant, $quantite);


retour("ok", ["idTransaction" => $idTransaction, "soldeAncien" => $soldeAncien, "soldeNouveau" => $soldeNouveau]);

?>

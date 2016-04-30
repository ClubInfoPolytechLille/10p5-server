<?php

require_once("../commun.php");

verifierDroit(2);

$requete = $db->prepare("SELECT idCarte, solde, decouvert FROM Clients");
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($idCarte, $solde, $decouvert);
$clients = [];
while ($requete->fetch()) {
    $client = ["idCarte" => $idCarte, "solde" => $solde];
    if ($droit >= 3) {
        $client["decouvert"] = !!$decouvert;
    }
    $clients[] = $client;
}
$requete->close();


retour("ok", ["clients" => $clients]);

?>

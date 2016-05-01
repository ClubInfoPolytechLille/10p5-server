<?php

require_once("../commun.php");

verifierDroit(3);

if (!(donne("idCarte") && donne("decouvert"))) {
    retour("requete_malformee");
}

if (!clientExiste(donne("idCarte"))) {
    retour("client_inconnu");
}

$decouvert = ($_POST['decouvert'] == 'true' || $_POST['decouvert'] == 1) ? 1 : 0;

$requete = $db->prepare("UPDATE Clients SET decouvert=? WHERE idCarte=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("ss", $decouvert, $_POST["idCarte"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

retour("ok");

?>

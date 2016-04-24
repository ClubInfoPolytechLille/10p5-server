<?php

require_once("../commun.php");


// Vérification des paramètres

if (!donne("jeton")) {
    retour("requete_malformee");
}

verifierJeton(donne("jeton"));

$jeton = donne("jeton");

$requete = $db->prepare("DELETE FROM Sessions WHERE jeton=?");
$requete->bind_param("s", $jeton);
$requete->execute();
$requete->close();

retour("ok");

?>

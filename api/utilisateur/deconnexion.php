<?php

require_once("../commun.php");


// Vérification des paramètres

if (!donne("jeton")) {
    retour("requete_malformee");
}

verifierJeton(donne("jeton"));

supprimerJeton(donne("jeton"));

retour("ok");

?>

<?php

require_once("../commun.php");

verifierDroit(0);

supprimerJeton(donne("jeton"));

retour("ok");

?>

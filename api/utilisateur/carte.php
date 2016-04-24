<?php

require_once("../commun.php");

verifierJeton(donne("jeton"));

if (!(donne("login") && donne("idCarte"))) {
    retour("requete_malformee");
}

if (!utilisateurExiste(donne("login"))) {
    retour("utilisateur_inconnu");
}

if ($login != donne("login")) {
    // Si on essaye de modifier l'idCarte de quelqu'un d'autre
    // on doit être le président
    verifierDroit(3);
}

$requete = $db->prepare("UPDATE Utilisateurs SET idCarte=? WHERE login=?");
$requete->bind_param("ss", $_POST["idCarte"], $_POST["login"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

retour("ok");

?>

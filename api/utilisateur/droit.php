<?php

require_once("../commun.php");

verifierDroit(3);

if (!(donne("login") && donne("droit"))) {
    retour("requete_malformee");
}

if (!utilisateurExiste(donne("login"))) {
    retour("utilisateur_inconnu");
}

$requete = $db->prepare("UPDATE Utilisateurs SET droit=? WHERE login=?");
if (!$requete) {
    retour("erreur_bdd_preparee", ["message" => $db->error]);
}
$requete->bind_param("ss", $_POST["droit"], $_POST["login"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

retour("ok");

?>

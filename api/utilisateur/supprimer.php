<?php

require_once("../commun.php");

verifierDroit(3);

if (!(donne("login"))) {
    retour("requete_malformee");
}

if (!utilisateurExiste(donne("login"))) {
    retour("utilisateur_inconnu");
}

$requete = $db->prepare("DELETE FROM Utilisateurs WHERE login=?");
$requete->bind_param("s", $_POST["login"]);
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->close();

retour("ok");

?>

<?php

require_once("../commun.php");

verifierDroit(3);

$requete = $db->prepare("SELECT login, idCarte, droit FROM Utilisateurs");
if (!$requete->execute()) {
    retour("erreur_bdd", ["message" => $requete->error]);
}
$requete->bind_result($login, $idCarte, $droit);
$utilisateurs = [];
while ($requete->fetch()) {
    $utilisateur = ["login" => $login, "idCarte" => $idCarte, "droit" => $droit];
    $utilisateurs[] = $utilisateur;
}
$requete->close();


retour("ok", ["utilisateurs" => $utilisateurs]);

?>

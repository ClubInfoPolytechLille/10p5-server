<?php

header('Access-Control-Allow-Origin: *'); // Histoire de pouvoir accéder à l'API depuis autre part que le serveur
header('Content-type: application/json'); // Histoire de faire comprendre au client que c'est du JSON

function retour($status, $donnees = array()) {
    $donnees['status'] = $status;
    echo json_encode($donnees);
}

?>

// Constantes
var JETON_TAILLE = 30 // Taille d'un jeton
var JETON_DUREE = 10*60 // Temps de validité du jeton en secondes

var TRANSACTION_CREATION = 1
var TRANSACTION_RECHARGEMENT = 2
var TRANSACTION_PAIEMENT = 3
var TRANSACTION_VIDANGE = 4

var TRANSACTION_DUREE = 60

// Informations de connexion

var login, droit, jeton, debut;

// Fonctions pour Materialize
$(function(){
    $('.button-collapse').sideNav();
    $('.modal-trigger').leanModal()
}); // end of document ready

// Fonctions communes

function APIbrute(chemin, donnees, cb) {
    var url = "api/" + chemin;
    $.post(url, donnees, function(data) {
        cb(data['status'], data);
    })

}

function toast(texte) {
    Materialize.toast(texte, 4000);
}

// Connexion
$("#connecter").click(function() {
    var login = $("#login").val();
    var mdp = $("#mdp").val();
    APIbrute("utilisateur/connexion", {login: login , mdp: mdp} , function(retour, donnees) {
        switch(retour) {
            case "identifiants_invalides":
                toast("Identifiants invalides")
                break;

            case "ok":
                login = donnees.login;
                droit = donnees.droit;
                jeton = donnees.jeton;
                toast("Correctement identifié en tant que " + login + " pour 10 minutes")
               break;

           default:
                toast("Erreur interne");
                break;
        }
    });
});

// Placeholder
function vendu() {
    var interieur = $("<span>").text("Vendu 1 bière à KAE1EET2YI (15,30 € → 13,50 €) ").append(
            $("<a>").attr("href", "#!").text("Annuler")
        );
    Materialize.toast(interieur, 4000);
}

function soldeInsuffisant() {
    $("#soldeInsuffisant").openModal();
}


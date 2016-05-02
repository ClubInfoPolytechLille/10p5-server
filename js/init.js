// Constantes
var JETON_TAILLE = 30 // Taille d'un jeton
var JETON_DUREE = 10*60 // Temps de validité du jeton en secondes

var TRANSACTION_CREATION = 1
var TRANSACTION_RECHARGEMENT = 2
var TRANSACTION_PAIEMENT = 3
var TRANSACTION_VIDANGE = 4

var TRANSACTION_DUREE = 60

// Fonctions pour Materialize
$(function(){
    $('.button-collapse').sideNav();
    $('.modal-trigger').leanModal()
});

// Application

var app = new Vue({
    el: 'body',
    data: {
        nomApplication: "10⁵",
        connecte: false,
        erreurTitre: '',
        erreurMessage: '',
        date: 1,
    },
    methods: {
        // API
        apiBrute: function(chemin, donnees, cb) {
            $.post('api/' + chemin, donnees, function(data) {
                cb(data['status'], data);
            })
        },
        // Affichage
        toast: function(texte) {
            Materialize.toast(texte, 4000);
        },
        erreur: function(retour, donnees) {
            this.erreurTitre = retour
            this.erreurMessage = donnees['message']
            $("#modalErreur").openModal();
        },
        // Fonctionnement
        connecter: function() {
            if (!this.peutConnecter) return
            var that = this;
            this.apiBrute("utilisateur/connexion", {login: this.login , mdp: this.mdp} , function(retour, donnees) {
                that.mdp = ''
                switch(retour) {
                    case "ok":
                        that.login = donnees.login
                        that.droit = donnees.droit
                        that.jeton = donnees.jeton
                        that.connecte = that.date
                        that.toast("Correctement identifié en tant que " + that.login + " pour 10 minutes")
                       break;

                    case "identifiants_invalides":
                        that.toast("Identifiants invalides")
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            })
        },
    },
    computed: {
        peutConnecter: function() {
            return this.login && this.mdp;
        },
        timer: function() {
            var secondes = this.connecte + JETON_DUREE - this.date
            return Math.floor(secondes/60) + ':' + (secondes % 60)
        }
    },
})

setInterval(function actualiserDate() {
    app.$data.date = Math.floor(Date.now()/1000)
}, 1000);


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


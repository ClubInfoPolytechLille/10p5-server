// Constantes
var JETON_TAILLE = 30 // Taille d'un jeton
var JETON_DUREE = 10*60 // Temps de validité du jeton en secondes

var TRANSACTION_CREATION = 1
var TRANSACTION_RECHARGEMENT = 2
var TRANSACTION_PAIEMENT = 3
var TRANSACTION_VIDANGE = 4

var TRANSACTION_DUREE = 60

var PEUT_NFC = false

// Fonctions pour Materialize
$(function(){
    $('.button-collapse').sideNav();
    $('.modal-trigger').leanModal()
});

// Application

var app = new Vue({
    el: 'body',
    data: {
        // Constantes
        PEUT_NFC: PEUT_NFC,
        // Affichage
        page: 'connexion',
        erreurTitre: '',
        erreurMessage: '',
        // Session
        connecte: false,
        date: 1,
    },
    methods: {
        // API
        apiBrute: function(chemin, donnees, cb) {
            $.post('api/' + chemin, donnees, function(data) {
                cb(data['status'], data);
            })
        },
        api: function(chemin, donnees, cb) {
            donnees['jeton'] = this.jeton
            this.apiBrute(chemin, donnees, cb)
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
                        that.toast("Correctement identifié en tant que " + that.login + " pour " + JETON_DUREE/60+ " minutes")
                        that.page = 'operations'
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
        creer: function() {
            if (!this.peutCreer) return
            var that = this
            this.api("client/ajouter", {idCarte: this.idCarte, solde: this.solde}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.toast("Client " + that.idCarte + " crée avec un solde de " + that.solde + " €")
                        break;

                    case "solde_negatif":
                        that.toast("Solde négatif")
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
        }
    },
    computed: {
        peutConnecter: function() {
            return this.login && this.mdp;
        },
        peutCreer: function() {
            return this.solde && (this.PEUT_NFC || this.idCarte)
        },
        timer: function() {
            var secondes = this.connecte + JETON_DUREE - this.date
            var minutes = Math.floor(secondes/60)
            var secondes = secondes % 60
            return  minutes + ':' + (secondes < 10 ? '0' : '') + secondes
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


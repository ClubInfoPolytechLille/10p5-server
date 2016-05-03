// Constantes
var JETON_TAILLE = 30 // Taille d'un jeton
var JETON_DUREE = 10*60 // Temps de validité du jeton en secondes

var TRANSACTION_CREATION = 1
var TRANSACTION_RECHARGEMENT = 2
var TRANSACTION_PAIEMENT = 3
var TRANSACTION_VIDANGE = 4

var TRANSACTION_DUREE = 60

var PEUT_NFC = false

// Préparation de l'interactivité
$(function(){
    $('.button-collapse').sideNav();
    $('.modal-trigger').leanModal()
    $('form').submit(function() { return false });
    $('[name=idCarte]').characterCounter();
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
        annuler(id) {
            var that = this
            this.api("annuler", {idTransaction: id}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.toast("Client " + donnees.client + " : " + donnees.soldeAncien + " → " + donnees.soldeNouveau)
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
        },
        transaction(id, texte) {
            var that = this
            var interieur = $('<span>').text(texte + ' ').append($('<a>').text('Annuler').one('click', function() {
                that.annuler(id)
            }))
            that.toast(interieur);
        },
        // Fonctionnement
        connecter: function() {
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

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            })
            return false
        },
        creer: function() {
            var that = this
            this.api("client/ajouter", {idCarte: this.idCarte, solde: this.solde}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.transaction(donnees.idTransaction, "Client " + that.idCarte + " crée avec un solde de " + that.solde + " €")
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
        },
        recharger: function() {
            var that = this
            this.api("client/recharger", {idCarte: this.idCarte, montant: this.credit}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.transaction(donnees.idTransaction, "Client " + that.idCarte + " rechargé : " + donnees.soldeAncien + " + " + that.credit + " → " + donnees.soldeNouveau + " €")
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
        },
        payer: function(quantite) {
            var that = this
            var options = {idCarte: this.idCarte}
            if (typeof(quantite) == 'number') {
                options.quantite = quantite
            } else {
                options.montant = that.prix
            }
            this.api("client/payer", options, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.transaction(donnees.idTransaction, "Client " + that.idCarte + " débité : " + donnees.soldeAncien + " - " + donnees.montant + " → " + donnees.soldeNouveau + " €")
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
        },
        vidanger: function() {
            var that = this
            this.api("client/vidange", {idCarte: this.idCarte}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.transaction(donnees.idTransaction, "Client " + that.idCarte + " vidé : " + donnees.soldeAncien + " → 0 €")
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
        },
    },
    computed: {
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


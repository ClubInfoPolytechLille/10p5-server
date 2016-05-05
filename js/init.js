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
        login: '',
        droit: '',
        jeton: '',
        connecte: false,
        date: 1,
        // Données
        clients: [],
        transactions: [],
    },
    methods: {
        // API
        apiBrute: function(chemin, donnees, cb) {
            $('body').css('opacity', 0.7)
            $.post('api/' + chemin, donnees).done(function(data) {
                cb(data['status'], data);
            }).error(function() {
                cb('erreur_communication', {});
            }).always(function() {
                $('body').css('opacity', 1)
            })
        },
        api: function(chemin, donnees, cb) {
            donnees['jeton'] = this.jeton
            this.apiBrute(chemin, donnees, cb)
        },
        actuClients: function() {
            var that = this
            this.api("client/liste", {}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.clients = donnees.clients
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            })
        },
        actuTransactions: function() {
            var that = this
            this.api("transactions", {}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        that.transactions = donnees.transactions
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            })
        },

        // Affichage
        toast: function(texte) {
            Materialize.toast(texte, 4000);
        },
        erreur: function(retour, donnees) {
            this.erreurTitre = retour
            this.erreurMessage = donnees.message
            $("#modalErreur").openModal();
        },
        annuler: function(id) {
            var that = this
            this.api("annuler", {idTransaction: id}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        for (transaction of that.transactions) {
                            if (transaction.id == id) {
                                transaction.valide = 0
                            }
                        }
                        that.toast("Client " + donnees.client + " : " + donnees.soldeAncien + " → " + donnees.soldeNouveau)
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
        },
        transaction: function(id, texte) {
            var that = this
            var interieur = $('<span>').text(texte + ' ').append($('<a>').text('Annuler').one('click', function() {
                that.annuler(id)
            }))
            that.toast(interieur);
        },
        decouvert: function(idCarte, decouvert, e) {
            var that = this
            // Hack pour récupérer la vraie valeur (decouvert peut mais pas obligatoirement avoir la bonne valeur tel qu'implémenté dans le HTML actuellmenent)
            if (typeof(e) == 'object') {
                decouvert = $(e.target).is(':checked')
            }
            this.api("client/decouvert", {idCarte: idCarte, decouvert: decouvert}, function(retour, donnees) {
                switch(retour) {
                    case "ok":
                        break;

                    default:
                        that.erreur(retour, donnees);
                        break;
                }
            });
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
            this.api("client/ajouter", {idCarte: this.idCarte, solde: this.solde, decouvert: this.decouvert}, function(retour, donnees) {
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

Vue.filter('date', function(timestamp) {
    return Date(timestamp).toLocaleString();
})

setInterval(function actualiserDate() {
    app.$data.date = Math.floor(Date.now()/1000)
}, 1000);



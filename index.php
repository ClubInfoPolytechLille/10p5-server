<!DOCTYPE html>
<html lang="fr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title>10⁵</title>

    <!-- CSS  -->
    <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection" />
</head>

<body>
    <!-- Réutilisable -->
    <div id="modalErreur" class="modal">
        <div class="modal-content">
            <h4>{{ erreurTitre }}</h4>
            <p v-if="modalGeneriqueContenu">{{ erreurMessage }}</p>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect btn-flat">Ça marche</a>
        </div>
    </div>
    <ul id="menuUtilisateur" class="dropdown-content">
        <li><a>Se déconnecter</a></li>
        <li><a>Changer de mot de passe</a></li>
        <li><a>Changer de carte</a></li>
        <li class="prezSeul"><a>Gestion des utilisateurs</a></li>
    </ul>
    <div id="passerCarte" class="modal">
        <div class="modal-content">
            <h4>Passez la carte du client</h4>
            <p>Veuillez passer la carte du client concerné pour finaliser l'action suivante : <strong>recharger 10 €</strong>.</p>
        </div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-red btn-flat">Annuler</a>
        </div>
    </div>
    <div id="soldeInsuffisant" class="modal">
        <div class="modal-content">
            <h4>Solde insuffisant</h4>
            <p>
                Le solde de la carte <strong>QUAELUY1RO</strong> n'est pas suffisant pour effectuer un paiement de 3,50 €.<br/> Solde actuel : 2,70 €<br/> Manquant : 0,80 €<br/>
            </p>
        </div>
        <div class="modal-footer">
            <a href="#rechargement" class="modal-action modal-close waves-effect waves-green btn-flat">Recharger</a>
            <a class="modal-action modal-close waves-effect waves-red btn-flat">Annuler</a>
        </div>
    </div>
    <div id="u_ajouter" class="modal">
        <form @submit="u_ajouter(u_nouveau)">
            <div class="modal-content">
                <h4>Ajouter un utilisateur</h4>
                <div class="input-field">
                    <i class="material-icons prefix">perm_identity</i>
                    <input type="text" name="login" v-model="u_nouveau.login" length="30">
                    <label for="u_nouveau.login">Login</label>
                </div>
                <p>
                    <input name="droit_nouveau" type="radio" id="droit_nouveau_1" v-model="u_nouveau.droit" value="1" />
                    <label for="droit_nouveau_1">BDE</label>
                </p>
                <p>
                    <input name="droit_nouveau" type="radio" id="droit_nouveau_2" v-model="u_nouveau.droit" value="2" />
                    <label for="droit_nouveau_2">Bar</label>
                </p>
                <p>
                    <input name="droit_nouveau" type="radio" id="droit_nouveau_3" v-model="u_nouveau.droit" value="3" />
                    <label for="droit_nouveau_3">Président</label>
                </p>
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input type="password" name="mdp" v-model="u_nouveau.mdp" length="72">
                    <label for="u_nouveau.mdp">Mot de passe</label>
                </div>
                <div class="input-field">
                    <i class="material-icons prefix">credit_card</i>
                    <input type="text" name="carte" length="8" v-model="u_nouveau.idCarte">
                    <label for="carte">Carte</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="modal-action modal-close waves-green" :disabled="!u_nouveau.login || !u_nouveau.droit || !u_nouveau.mdp">Créer</button>
                <button type="reset" class="modal-action modal-close waves-red">Annuler</button>
            </div>
        </form>
    </div>

    <!-- Page -->
    <nav role="navigation">

        <div>
            <div>
                <a class="brand-logo" v-if="page == 'connexion'">10⁵</a>
                <div class="left" v-else>
                    <a class="breadcrumb">10⁵</a>
                    <a class="breadcrumb" v-if="page == 'operations'">Opérations</a>
                    <a class="breadcrumb" v-if="page == 'gestion'">Gestion</a>
                </div>
                <ul class="right hide-on-med-and-down">
                    <li v-if="moi.connecte">
                        <a class="dropdown-button" @click="deconnecter">
                            <i class="material-icons">perm_identity</i> {{ moi.login }} (<i class="material-icons">timer</i> {{ timer }})
                        </a>
                    </li>
                    <li v-if="connecte && page == 'operations'">
                        <a @click="changerPage('gestion')">
                            <i class="material-icons">settings</i> Gestion
                        </a>
                    </li>
                    <li v-if="connecte && page == 'gestion'">
                        <a @click="changerPage('operations')">
                            <i class="material-icons">play_arrow</i> Opérations
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <ul class="tabs" v-show="page == 'operations'">
                    <li class="tab col s3"><a href="#creation">Création</a></li>
                    <li class="tab col s3"><a href="#rechargement">Rechargement</a></li>
                    <li class="tab col s3"><a class="active" href="#paiement">Paiement</a></li>
                    <li class="tab col s3"><a href="#vidange">Vidange</a></li>
                </ul>
                <ul class="tabs" v-show="page == 'gestion'">
                    <li class="tab col s3"><a href="#clients">Clients</a></li>
                    <li class="tab col s3"><a href="#transactions">Transactions</a></li>
                    <li class="tab col s3"><a href="#utilisateurs">Utilisateurs</a></li>
                    <li class="tab col s3"><a href="#statistiques">Statistiques</a></li>
                </ul>
            </div>
            <ul class="side-nav">
                <todo></todo>
            </ul>
        </div>
    </nav>

    <div id="main">
        <div class="container" v-show="page == 'connexion'">
            <h4>Se connecter</h4>
            <div>
                <h5>Avec votre identifiant et votre mot de passe</h5>
                <form @submit="connecter">
                    <div class="input-field">
                        <i class="material-icons prefix">account_circle</i>
                        <input type="text" name="login" v-model="login">
                        <label for="login">Identifiant</label>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">vpn_key</i>
                        <input type="password" name="mdp" v-model="mdp">
                        <label for="mdp">Mot de passe</label>
                    </div>
                    <button :disabled="!login || !mdp">Se connecter</button>
                </form>
            </div>
        </div>
        <div v-if="PEUT_NFC">
            <h5>Avec votre carte étudiant</h5>
            <p>Passez-la simplement devant le lecteur.</p>
        </div>

        <div class="container" v-show="page == 'operations'">
            <div class="row">
                <div id="creation">
                    <h4>Créer un nouveau client</h4>
                    <form @submit="creer">
                        <div class="input-field" v-if="!PEUT_NFC">
                            <i class="material-icons prefix">credit_card</i>
                            <input type="text" name="idCarte" length="8" v-model="idCarte">
                            <label for="idCarte">Identifiant de la carte</label>
                        </div>
                        <div class="input-field">
                            <i class="material-icons prefix">trending_flat</i>
                            <input type="number" name="solde" v-model="solde" min="0.01" max="999.99" step="0.01">
                            <label for="solde">Solde initial (€)</label>
                        </div>
                        <div class="row" v-if="droit >= 3">
                            <strong>Découvert autorisé</strong>
                            <div class="switch">
                                <label>
                                    Non
                                    <input v-model="decouvert" type="checkbox">
                                    <span class="lever"></span>
                                    Oui
                                </label>
                            </div>
                        </div>
                        <button type="submit" :disabled="!solde || (!PEUT_NFC && !idCarte)">Créer</button>
                    </form>
                </div>
                <div id="rechargement">
                    <h4>Recharger un client</h4>
                    <form @submit="recharger">
                        <div class="input-field" v-if="!PEUT_NFC">
                            <i class="material-icons prefix">credit_card</i>
                            <input type="text" name="idCarte" length="8" v-model="idCarte">
                            <label for="idCarte">Identifiant de la carte</label>
                        </div>
                        <div class="input-field">
                            <i class="material-icons prefix">trending_up</i>
                            <input type="number" name="credit" v-model="credit" min="0.01" max="999.99" step="0.01">
                            <label for="credit">Crédit (€)</label>
                        </div>
                        <button type="submit" :disabled="!credit || (!PEUT_NFC && !idCarte)">Recharger</button>
                    </form>
                </div>
                <div id="paiement">
                    <h4>Vendre à un client</h4>
                    <form>
                        <div class="input-field" v-if="!PEUT_NFC">
                            <i class="material-icons prefix">credit_card</i>
                            <input type="text" name="idCarte" length="8" v-model="idCarte">
                            <label for="idCarte">Identifiant de la carte</label>
                        </div>
                        <div class="row">
                            <h5>En spécifiant le nombre de consommations</h5>
                        </div>
                        <div id="grilleBieres">
                            <div>
                                <div v-for="i in [1, 2, 3, 4, 5, 6]"><button type="button" @click="payer(i)" :disabled="prix || (!PEUT_NFC && !idCarte)">{{ i }}</button></div>
                            </div>
                        </div>
                        <div class="row">
                            <h5>En spécifiant un montant</h5>
                        </div>
                        <div class="input-field">
                            <i class="material-icons prefix">trending_down</i>
                            <input type="number" name="prix" v-model="prix" min="0.01" max="999.99" step="0.01">
                            <label for="prix">Prix (€)</label>
                        </div>
                        <button type="submit" @click="payer" :disabled="!prix || (!PEUT_NFC && !idCarte)">Payer</button>
                    </form>
                </div>
                <div id="vidange">
                    <h4>Vider le contenu de la carte d'un client</h4>
                    <form @submit="vidanger">
                        <div class="input-field" v-if="!PEUT_NFC">
                            <i class="material-icons prefix">credit_card</i>
                            <input type="text" name="idCarte" length="8" v-model="idCarte">
                            <label for="idCarte">Identifiant de la carte</label>
                        </div>
                        <button type="submit" :disabled="!PEUT_NFC && !idCarte">Vider</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container" v-show="page == 'gestion'">
            <div class="row">
                <div id="clients">
                    <h4>Liste des clients</h4>
                    <button @click="actuClients">Rafraîchir</button>
                    <ul class="collapsible popout" data-collapsible="accordion">
                        <li v-for="client in clients" @click="idCarte = client.idCarte">
                            <div class="collapsible-header">
                                <i class="material-icons">perm_identity</i> {{ client.idCarte }} : {{ client.solde }} €
                            </div>
                            <div class="collapsible-body">
                                <ul>
                                    <li v-if="droit >= 3">
                                        <strong>Découvert autorisé</strong>
                                        <div class="switch">
                                            <label>
                                                Non
                                                <input v-model="client.decouvert" type="checkbox" @click="c_decouvert(client, $event)">
                                                <span class="lever"></span>
                                                Oui
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                                <h5>Transactions</h5>
                                <todo></todo>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="transactions">
                    <h4>Liste des transactions</h4>
                    <button @click="actuTransactions">Rafraîchir</button>
                    <ul class="collapsible popout" data-collapsible="accordion">
                        <li v-for="transaction in transactions | orderBy 'date' -1">
                            <div class="collapsible-header" :class="{'red': !transaction.valide}">
                                <span v-if="transaction.type == 1">
                                    <i class="material-icons">trending_flat</i> {{ transaction.client }} : {{ transaction.montant }} €
                                </span>
                                <span v-if="transaction.type == 2">
                                    <i class="material-icons">trending_up</i> {{ transaction.client }} : + {{ transaction.montant }} €
                                </span>
                                <span v-if="transaction.type == 3">
                                    <i class="material-icons">trending_down</i> {{ transaction.client }} : - {{ transaction.montant }} €
                                    <span v-if="transaction.quantite != 0">({{ transaction.quantite }} consommation<span v-if="transaction.quantite >= 2">s</span>)</span>
                                </span>
                                <span v-if="transaction.type == 4">
                                    <i class="material-icons">delete</i> {{ transaction.client }} : - {{ transaction.montant }} €
                                </span>
                            </div>
                            <div class="collapsible-body">
                                <ul>
                                    <li>
                                        <strong>Type :</strong>
                                        <span v-if="transaction.type == 1">Création</span>
                                        <span v-if="transaction.type == 2">Rechargement</span>
                                        <span v-if="transaction.type == 3">Date</span>
                                        <span v-if="transaction.type == 4">Vidange</span>
                                    </li>
                                    <li>
                                        <strong>Date :</strong> <span v-text="transaction.date | date"></span>
                                    </li>
                                    <li>
                                        <strong>Utilisateur :</strong> {{ transaction.utilisateur }}
                                    </li>
                                </ul>
                                <button @click="annuler(transaction.id)" :disabled="!transaction.valide">Annuler</button>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="utilisateurs">
                    <h4>{{ moi.login }}</h4>
                    <umodifier :utilisateur="moi"></umodifier>
                    <button class="orange" @click="deconnecter">Se déconnecter</button>

                    <h4>Liste des utilisateurs</h4>
                    <button @click="actuUtilisateurs">Rafraîchir</button>
                    <ul class="collapsible popout" data-collapsible="accordion">
                        <li v-for="utilisateur in utilisateurs">
                            <div class="collapsible-header">
                                <i class="material-icons" v-if="utilisateur.droit == 1">assignment_ind</i>
                                <i class="material-icons" v-if="utilisateur.droit == 2">perm_identity</i>
                                <i class="material-icons" v-if="utilisateur.droit == 3">supervisor_account</i> {{ utilisateur.login }}
                            </div>
                            <div class="collapsible-body">
                                <umodifier :utilisateur="utilisateur"></umodifier>
                                <button disabled>Supprimer <todo></todo></button>

                                <h5>Transactions</h5>
                                <todo></todo>
                            </div>
                        </li>
                    </ul>
                    <div class="fixed-action-btn">
                        <button @click="modal('u_ajouter')">
                            <i class="large material-icons">add</i>
                        </button>
                    </div>
                </div>
            </div>
            <div id="statistiques">
                <h4>Statistiques</h4>
                <h5>Plage de temps <todo></todo></h5>
                <p>Du <strong>début</strong> jusqu'à <strong>maintenant</strong></p>
                <button @click="actuStatistiques">Rafraîchir</button>

                <h5>Chiffre d'affaires</h5>
                <ul>
                    <li><strong>Bénéfices :</strong> {{ statistiques.benefices }} €</li>
                    <li><strong>Recettes :</strong> {{ statistiques.recettes }} €</li>
                    <li><strong>Dettes :</strong> {{ statistiques.dettes }} €</li>
                </ul>
                <h5>Factures</h5>
                <ul>
                    <li><strong>BDE :</strong> {{ statistiques.factureBDE }} €</li>
                </ul>
                <h5>Divers</h5>
                <ul>
                    <li><strong>Nombre de clients :</strong> {{ statistiques.clientsNb }}</li>
                    <li><strong>Consommations :</strong> {{ statistiques.consommationsNb }}</li>
                    <li><strong>Solde moyen :</strong> {{ statistiques.soldeMoy }} €</li>
                </ul>
            </div>
        </div>
    </div>


    </div>

    <footer class="page-footer">
        <div class="footer-copyright">
            <div class="container">
            10p5 <?php echo exec("git describe --tags --dirty"); ?> © Copyright 2016, <a href="http://clubinfo.plil.net">Le Club Info Polytech Lille</a>
            </div>
        </div>
    </footer>

    <!-- Modèles -->
    <script type="text/template" id="todo">
        <div class="chip green">
            Prochainement
        </div>
    </script>
    <script type="text/template" id="u_modifier">
        <form @change="u_droit(utilisateur)">
            <p>
                <input :name="'droit_' + utilisateur.login" type="radio" :id="'droit_' + utilisateur.login + '_1'" v-model="utilisateur.droit" value="1" :disabled="moi.droit < 3"/>
                <label :for="'droit_' + utilisateur.login + '_1'">BDE</label>
            </p>
            <p>
                <input :name="'droit_' + utilisateur.login" type="radio" :id="'droit_' + utilisateur.login + '_2'" v-model="utilisateur.droit" value="2" :disabled="moi.droit < 3"/>
                <label :for="'droit_' + utilisateur.login + '_2'">Bar</label>
            </p>
            <p>
                <input :name="'droit_' + utilisateur.login" type="radio" :id="'droit_' + utilisateur.login + '_3'" v-model="utilisateur.droit" value="3" :disabled="moi.droit < 3"/>
                <label :for="'droit_' + utilisateur.login + '_3'">Président</label>
            </p>
        </form>
        <form @submit="u_mdp(utilisateur)">
            <div class="input-field">
                <i class="material-icons prefix">vpn_key</i>
                <input type="password" name="mdp" v-model="utilisateur.mdp" length="72">
                <label for="utilisateur.mdp">Mot de passe</label>
            </div>
            <button :disabled="!utilisateur.mdp">Changer de mot de passe</button>
        </form>
        <form @submit="u_carte(utilisateur)">
            <div class="input-field">
                <i class="material-icons prefix">credit_card</i>
                <input type="text" name="carte" length="8" v-model="utilisateur.idCarte">
                <label for="utilisateur.idCarte">Carte</label>
            </div>
            <button :disabled="!utilisateur.idCarte">Changer de carte</button>
        </form>
    </script>

    <!--  Scripts-->
    <script src="lib/jquery/dist/jquery.js"></script>
    <script src="lib/Materialize/dist/js/materialize.js"></script>
    <script src="lib/vue/dist/vue.js"></script>
    <script src="js/init.js"></script>

</body>

</html>

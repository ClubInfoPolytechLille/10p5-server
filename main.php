<?php
include("header.php");
?>
  <!-- Réutilisable -->
  <ul id="menuUtilisateur" class="dropdown-content">
      <li><a href="#!">Se déconnecter</a></li>
      <li><a href="#!">Changer de mot de passe</a></li>
      <li><a href="#!">Changer de carte</a></li>
      <li class="prezSeul"><a href="#!">Gestion des utilisateurs</a></li>
  </ul>
  <div id="passerCarte" class="modal">
      <div class="modal-content">
          <h4>Passez la carte du client</h4>
          <p>Veuillez passer la carte du client concerné pour finaliser l'action suivante : <strong>recharger 10 €</strong>.</p>
      </div>
      <div class="modal-footer">
          <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Annuler</a>
      </div>
  </div>
  <div id="soldeInsuffisant" class="modal">
      <div class="modal-content">
          <h4>Solde insuffisant</h4>
          <p>
            Le solde de la carte <strong>QUAELUY1RO</strong> n'est pas suffisant pour effectuer un paiement de 3,50 €.<br/>
            Solde actuel : 2,70 €<br/>
            Manquant : 0,80 €<br/>
          </p>
      </div>
      <div class="modal-footer">
          <a href="#rechargement" class="modal-action modal-close waves-effect waves-green btn-flat">Recharger</a>
          <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Annuler</a>
      </div>
  </div>

  <!-- Page -->
  <nav class="red" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">10⁵</a>
      <ul class="right hide-on-med-and-down">
          <li><a class="dropdown-button" href="#!" data-activates="menuUtilisateur"><i class="material-icons">perm_identity</i> vigou (<i class="material-icons">timer</i> 3:12)</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="#">TODO</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
    <div class="col s12 red">
      <ul class="tabs red">
        <li class="tab col s3"><a href="#creation">Création</a></li>
        <li class="tab col s3"><a href="#rechargement">Rechargement</a></li>
        <li class="tab col s3"><a class="active" href="#paiement">Paiement</a></li>
        <li class="tab col s3"><a href="#vidange">Vidange</a></li>
      </ul>
    </div>
  </nav>
  <div class="section no-pad-bot" id="main">
    <div class="container">
      <br><br><br>
      <div class="row center">
          <div id="creation">
              <h5>Créer un nouveau client</h5>
              <div class="row">
                <form class="col s12">
                    <div class="input-field col s12">
                        <input type="number" id="solde">
                        <label for="solde">Solde initial (€)</label>
                    </div>
                  <div class="row center">
                    <a href="#!" id="creer" class="btn-large waves-effect waves-light">Créer</a>
                  </div>
                </form>
              </div>
          </div>
          <div id="rechargement">
              <h5>Recharger un client</h5>
              <div class="row">
                <form class="col s12">
                    <div class="input-field col s12">
                        <input type="number" id="credit">
                        <label for="credit">Crédit (€)</label>
                    </div>
                  <div class="row center">
                    <a href="#passerCarte" id="recharger" class="btn-large waves-effect waves-light btn modal-trigger">Recharger</a>
                  </div>
                </form>
              </div>
          </div>
          <div id="paiement">
              <h5>Vendre à un client</h5>
              <div class="row">
                <form class="col s12">
                    <div id="grilleBieres">
                        <div class="row">
                            <div class="col s4"><a onclick="vendu()" class="waves-effect waves-light btn">1</a></div>
                            <div class="col s4"><a class="waves-effect waves-light btn">2</a></div>
                            <div class="col s4"><a class="waves-effect waves-light btn">3</a></div>
                        </div>
                        <div class="row">
                            <div class="col s4"><a class="waves-effect waves-light btn">4</a></div>
                            <div class="col s4"><a class="waves-effect waves-light btn">5</a></div>
                            <div class="col s4"><a class="waves-effect waves-light btn">6</a></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input type="number" id="prix">
                            <label for="prix">Prix (€)</label>
                        </div>
                    </div>
                  <div class="row center">
                    <a href="#!" id="payer" onclick="soldeInsuffisant()" class="btn-large waves-effect waves-light">Payer</a>
                  </div>
                </form>
              </div>
          </div>
          <div id="vidange">
              <h5>Vider le contenu de la carte d'un client</h5>
              <div class="row center">
                <a href="#!" id="payer" class="btn-large waves-effect waves-light">Vider</a>
              </div>
          </div>
      </div>
    </div>
  </div>
<?php
include "footer.php"
?>

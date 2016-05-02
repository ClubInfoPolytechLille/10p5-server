<?php
include("header.php");
?>
  <!-- Réutilisable -->
  <!-- Page -->
  <nav class="red" role="navigation">
    <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">10⁵</a>
      <ul class="right">
      </ul>
    </div>
  </nav>

  <div class="section no-pad-bot" id="main">
    <div class="container">
      <br><br><br>
      <h4>Se connecter</h4>
          <h5>Avec votre identifiant et votre mot de passe</h4>
          <div class="row">
            <form class="col s12">
              <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="login">
                    <label for="login">Identifiant</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                    <input type="password" id="mdp">
                    <label for="mdp">Mot de passe</label>
                </div>
              </div>
              <div class="row">
                <a href="main.php" id="connecter" class="btn-large waves-effect waves-light">Se connecter</a>
              </div>
            </form>
          </div>
          <h5>Avec votre carte étudiant</h5>
          <p>Passez-la simplement devant le lecteur</p>
      </div>
    </div>
  </div>
<?php
include "footer.php"
?>

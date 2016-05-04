<?php
/** 
 * Page d'accueil de l'application web AppliFrais
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // page inaccessible si visiteur non connecté
  if ( ! estVisiteurConnecte() ) 
  {
        header("Location: cSeConnecter.php");  
  }
  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");
?>
  <!-- Division principale -->
  <div id="contenu">
      <h2>Bienvenue sur l'intranet GSB</h2>
      <div class="textaccueil">
      <p align="center">
      </br>
        GSB, vous présente leur site de gestion de note de frais.</br>
        Vous trouverez différents onglets disponibles sur le sommaire, pour mettre à jour vos notes de frais. </br>
        Ainsi que visualiser vos notes de frais, de plus voir l'état de celle-ci. Si votre comptable valide ou non la note de frais.

      </p>
    </div>
  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>

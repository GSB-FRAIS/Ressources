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
      <h2>Vous rencontrez un problème ?</h2>
      <div class="textaccueil">
      <p align="center">
      </br>
        Si vous avez besoin d'aide concernant certains de nos services ou si vous rencontrez des problèmes techniques, nous nous ferons un plaisir de vous dépanner. 
        Nous vous invitons à vous joindre sur <a href="http://flo59.synology.me/PPE2/mantisbt/login_page.php" title="Page Support" style="color:blue; background-color:white;font-size:13px;">ce site</a> pour nous faire part de votre demande.</br>
        Nous pourrez vous connectez via notre nom d'utilisateur et mot de passe de l'application Gsb_Frais.</br>
        Vous pouvez renseignez votre demande dans la partie "Rapporter un bogue".</br></br>
        Merci de votre patience, nous répondrons à notre demande au plus vite.
      </p>
    </div>
  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?>

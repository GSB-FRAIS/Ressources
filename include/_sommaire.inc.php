<?php
/** 
 * Contient la division pour le sommaire, sujet à des variations suivant la 
 * connexion ou non d'un utilisateur, et dans l'avenir, suivant le type de cet utilisateur 
 * @todo  RAS
 */

?>
    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    <?php      
      if (estVisiteurConnecte() ) {
          $idUser = obtenirIdUserConnecte() ;
          $lgUser = obtenirDetailVisiteur($idConnexion, $idUser);
          $nom = $lgUser['nom'];
          $prenom = $lgUser['prenom'];
          $metier = $lgUser['metier'];           
    ?>
    <div class="nomu">
        <h2>
    <?php  
            echo $nom . " " . $prenom;
    ?>
        </h2>
    </div>
        <h3> <?php echo $metier ?></h3>        
    <?php
       }
    ?>  
      </div>  
<?php      
  if (estVisiteurConnecte() ) {
    if($metier=="Visiteur"){
      ?>
        <ul id="menuList">
           <li class="smenu">
              <a href="cAccueil.php" title="Page d'accueil">Accueil</a>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
           </li>
           <li class="smenu">
              <a href="cSaisieFicheFrais.php" title="Saisie fiche de frais du mois courant">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="cConsultFichesFrais.php" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
           <li class="smenu">
              <a href="cNousContacter.php" title="Problème Rencontré">Nous Contacter</a>
           </li>
         </ul>

      <?php
       }else if($metier=="Comptable"){
        ?>
        <ul id="menuList">
           <li class="smenu">
              <a href="cAccueil.php" title="Page d'accueil">Accueil</a>
           </li>
            <li class="smenu">
              <a href="cGestionFicheFrais.php" title="Gestion des fiches de frais"><font color="red">Gestion fiche de frais</font></a>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
           </li>
           <li class="smenu">
              <a href="cSaisieFicheFrais.php" title="Saisie fiche de frais du mois courant">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="cConsultFichesFrais.php" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
            <li class="smenu">
              <a href="cNousContacter.php" title="Problème Rencontré">Nous Contacter</a>
           </li>
         </ul>
        <?php
      }else if($metier=="Admin"){
        ?>
        <ul id="menuList">
           <li class="smenu">
              <a href="cAccueil.php" title="Page d'accueil">Accueil</a>
           </li>
            <li class="smenu">
              <a href="newuser.php" title="Nouvel utilisateur">Creer un utilisateur</a>
           </li>
           <li class="smenu">
              <a href="cSeDeconnecter.php" title="Se déconnecter">Se déconnecter</a>
           </li>
            <li class="smenu">
              <a href="cNousContacter.php" title="Problème Rencontré">Nous Contacter</a>
           </li>
         </ul>
        <?php
      }
          // affichage des éventuelles erreurs déjà détectées
          if ( nbErreurs($tabErreurs) > 0 ) {
              echo toStringErreurs($tabErreurs) ;
          }
  }
        ?>
    </div>
    
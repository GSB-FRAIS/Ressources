<?php
/** 
 * Script de contrôle et d'affichage du cas d'utilisation "Consulter une fiche de frais"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // page inaccessible si visiteur non connecté
  if ( ! estVisiteurConnecte() ) {
      header("Location: cSeConnecter.php");  
  }
 
  // acquisition des données entrées, ici le numéro de mois et l'étape du traitement
  $moisSaisi=lireDonneePost("lstMois", "");
  $etape=lireDonneePost("etape",""); 

  if ($etape != "demanderConsult" && $etape != "validerConsult") {
      // si autre valeur, on considère que c'est le début du traitement
      $etape = "demanderConsult";        
  } 
  if ($etape == "validerConsult") { // l'utilisateur valide ses nouvelles données
                
      // vérification de l'existence de la fiche de frais pour le mois demandé
      $existeFicheFrais = existeFicheFrais($idConnexion, $moisSaisi, obtenirIdUserConnecte());
      // si elle n'existe pas, on la crée avec les élets frais forfaitisés à 0
      if ( !$existeFicheFrais ) {
          ajouterErreur($tabErreurs, "Le mois demandé est invalide");
      }
      else {
          // récupération des données sur la fiche de frais demandée
          $tabFicheFrais = obtenirDetailFicheFrais($idConnexion, $moisSaisi, obtenirIdUserConnecte());
      }
  }     
$id = $_GET['id'];
$lgMois = $_GET['mois'];                             
ob_start(); 
  ?>
  <page>
  <link href="./styles/stylespdf.css" rel="stylesheet" type="text/css" />
  <!-- Division principale -->
  <div id="contenu">
    <p align="center"><img src="images/logo.png"></p> 
      <div class="test">
<?php      
            // demande de la requête pour obtenir la liste des éléments 
            // forfaitisés du visiteur connecté pour le mois demandé
            $req = obtenirReqEltsForfaitFicheFrais3();
            $idJeuEltsFraisForfait = mysqli_query($idConnexion, $req);
            echo mysqli_error($idConnexion);
            $lgEltForfait = mysqli_fetch_assoc($idJeuEltsFraisForfait);
            // parcours des frais forfaitisés du visiteur connecté
            // le stockage intermédiaire dans un tableau est nécessaire
            $tabEltsFraisForfait = array();
            while ( is_array($lgEltForfait) ) {
                $tabEltsFraisForfait[$lgEltForfait["libelle"]] = $lgEltForfait["quantite"];
                $lgEltForfait = mysqli_fetch_assoc($idJeuEltsFraisForfait);
            }
            mysqli_free_result($idJeuEltsFraisForfait);

            $sql = obtenirNomPrenom2(); 
            $result = mysqli_query($idConnexion, $sql);
            $row = mysqli_fetch_assoc($result); ?>
          <div class="entete">
            <p style="font-size:15px;font-weight:bold">   
              <?php echo "Fiche de frais de : ".$row["nom"]."  ".$row["prenom"]; ?>
            </p>
          </div>
            <br><br>
  	<p><table class="listelourde">
              <tr>
        
            <?php
            // premier parcours du tableau des frais forfaitisés du visiteur connecté
            // pour afficher la ligne des libellés des frais forfaitisés
            foreach ( $tabEltsFraisForfait as $unLibelle => $uneQuantite ) {
            ?>
                <th><?php echo $unLibelle ; ?></th>
            <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            // second parcours du tableau des frais forfaitisés du visiteur connecté
            // pour afficher la ligne des quantités des frais forfaitisée
            foreach ( $tabEltsFraisForfait as $unLibelle => $uneQuantite ) {
            ?>
                <td class="qteForfait"><?php echo $uneQuantite ; ?></td>
            <?php
            }
            ?>
        </tr>
    </table></p>
    <br>
  	<p><table class="listeLegere">
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class="montant">Montant</th>                
             </tr>
<?php          
            // demande de la requête pour obtenir la liste des éléments hors
            // forfait du visiteur connecté pour le mois demandé
            $req = obtenirReqEltsHorsForfaitFicheFrais3();
            $idJeuEltsHorsForfait = mysqli_query($idConnexion, $req);
            $lgEltHorsForfait = mysqli_fetch_assoc($idJeuEltsHorsForfait);
            
            // parcours des éléments hors forfait 
            while ( is_array($lgEltHorsForfait) ) {
            ?>
                <tr>
                   <td><?php echo $lgEltHorsForfait["date"] ; ?></td>
                   <td><?php echo filtrerChainePourNavig($lgEltHorsForfait["libelle"]) ; ?></td>
                   <td><?php echo $lgEltHorsForfait["montant"] ; ?></td>
                </tr>
            <?php
                $lgEltHorsForfait = mysqli_fetch_assoc($idJeuEltsHorsForfait);
            }
            mysqli_free_result($idJeuEltsHorsForfait);
  ?>

  </table></p>
  <p>
    <?php
       $sql = obtenirtotal(); 
       $result = mysqli_query($idConnexion, $sql);
       $row = mysqli_fetch_assoc($result);?>
       <p align="center">
       <?php echo "Le montant total pour l'ensemble des frais forfaitisés est de : ".$row["total"]."€";
       $totalf = $row["total"];
       ?>
      </p>
     <br>
    <?php
       $sql = obtenirtotal2(); 
       $result = mysqli_query($idConnexion, $sql);
       $row = mysqli_fetch_assoc($result);?>
       <p align="center">    
       <?php echo "Le montant total pour l'ensemble des frais hors forfait est de : ".$row["total2"]."€";
       $totalh = $row["total2"];
       ?>
       </p>
       <br><br>
       <?php
       $toto = $totalf + $totalh;?>
       <div class="toto">
         <p align="center">
          <?php echo "Le total des frais pris en compte est de : " .$toto."€"; ?>
         </p>
       </div>
  </p>
</div>
</div>
<br><br>
<div class="sign">
  <?php $date = date("d-m-Y");
  Print("Fait le $date");
  ?>
  <p>Vu l'agent comptable</p>
  <p><img style="width:30%"src="images/sign.jpg"></p>
</div>
</page>
<?php 
    $content = ob_get_clean(); 
    require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF('P','A4','fr');
    $html2pdf->WriteHTML($content);
    $html2pdf->Output('exemple.pdf');
?>
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
  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");
  
  // acquisition des données entrées, ici le numéro de mois et l'étape du traitement
  $moisSaisi=lireDonneePost("lstMois", "");
  $etape=lireDonneePost("etape",""); 

  if ($etape != "demanderConsult" && $etape != "validerConsult") {
      // si autre valeur, on considère que c'est le début du traitement
      $etape = "demanderConsult";        
  } 
  /*if ($etape == "validerConsult") { // l'utilisateur valide ses nouvelles données
                
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
  } */                                 

  ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script>
    function aaa() {
        var jqxhr = $.ajax({
            method: "POST",
            url: "cModifEtat.php",
            data: { id: $("#nom").val(), mois: $("#lstMois").val()}
            })
            .done(function( msg ) {
              alert( msg );
            });
        }

    function bbb() {
        var jqxhr = $.ajax({
            method: "POST",
            url: "cRefusEtat.php",
            data: { id: $("#nom").val(), mois: $("#lstMois").val()}
            })
            .done(function( msg ) {
              alert( msg );
            });
        }
  </script>

  <!-- Division principale -->
  <div id="contenu">
      <h2>Mes fiches de frais</h2>
      <h3>Mois à sélectionner : </h3>
      <form action="cGestionFicheFrais.php" method="post" id="form1">
      <div class="corpsForm">
          <input type="hidden" name="etape" value="validerConsult" />
      <div class="corpsForm2">
        <p>
          <label for="lstMois">Mois : </label>
            <select id="lstMois" name="lstMois" title="Sélectionnez le mois souhaité pour la fiche de frais">
                <?php
                    // on propose tous les mois pour lesquels le visiteur a une fiche de frais
                    $req = obtenirReqMoisFicheFrais(obtenirIdUserConnecte());
                    $idJeuMois = mysqli_query($idConnexion, $req);
                    $lgMois = mysqli_fetch_assoc($idJeuMois);
                    while ( is_array($lgMois) ) {
                        $mois = $lgMois["mois"];
                        $noMois = intval(substr($mois, 4, 2));
                        $annee = intval(substr($mois, 0, 4));
                ?>    
                <option value="<?php echo $mois; ?>"<?php if ($moisSaisi == $mois) { ?> selected="selected"<?php } ?>><?php echo obtenirLibelleMois($noMois) . " " . $annee; ?></option>
                <?php
                        $lgMois = mysqli_fetch_assoc($idJeuMois);        
                    }
                    mysqli_free_result($idJeuMois);
                ?>
            </select>
        </p>

      <p>
        <label for="nom">Nom : </label>
        <?php 
        $result=obtenirNomPrenom($idConnexion);
        ?>
        <select id="nom" name="nom" title="Sélectionner le personne souhaitée">
        <?php
        while($row_user=mysqli_fetch_object($result)){
          ?>
          <option value="<?php echo $row_user->id?>" <?php if(isset($_POST['nom'])){if($row_user->id==$_POST['nom']){echo 'selected=selected';}} ?>><?php  echo $row_user->nom.", ".$row_user->prenom;?></option>
          <?php
        } 
        ?>
        </select>
        * Ne sont affichées que les personnes qui possèdent des notes de frais
      </p>
      </div>
    </div>  
      <div class="piedForm">
      <p>
        <input id="submit" type="submit" value="Valider" size="20" title="Demandez à consulter cette fiche de frais" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
      </div>
        
      </form>
<?php      

// demande et affichage des différents éléments (forfaitisés et non forfaitisés)
// de la fiche de frais demandée, uniquement si pas d'erreur détecté au contrôle
    if ( $etape == "validerConsult" ) {
        if ( nbErreurs($tabErreurs) > 0 ) {
            echo toStringErreurs($tabErreurs) ;
        }
        else {
?>
    <h3>Fiche de frais du mois de <?php echo obtenirLibelleMois(intval(substr($moisSaisi,4,2))) . " " . substr($moisSaisi,0,4); ?> : 
    <em style="color:red">    
    <?php 
        $sql = "select idVisiteur, libelle FROM fichefrais ff
                inner join etat e on e.id = ff.idEtat
                where ff.idVisiteur = '". $_POST['nom'] . "' and mois ='" . $_POST['lstMois'] ."'"; 
        $result = mysqli_query($idConnexion, $sql);
        $row = mysqli_fetch_assoc($result);
        echo "Status : " .$row['libelle']."."; ?>
    </em>
    <div class="encadre">
    <div class="gauche">

    <p><?php 
    $sql = "select nom,prenom from user where id = '" . $_POST['nom'] . "'";
    $result = mysqli_query($idConnexion, $sql);
    $row = mysqli_fetch_assoc($result);
    echo "Fiche de frais de : ".$row['nom']."  ".$row['prenom']; ?></p>
    </div>
    <div class="droite">
     <a href="print.php<?php echo "?id=".$_POST['nom']."&mois=".$_POST['lstMois'] ?>"><img src="images/print.png"></a>
     </div>
    
<?php      
            // demande de la requête pour obtenir la liste des éléments 
            // forfaitisés du visiteur connecté pour le mois demandé
            $req = obtenirReqEltsForfaitFicheFrais2();
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
            ?>
  	<table class="listeLegere">
       
  	   <caption>Quantités des éléments forfaitisés</caption>
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
    </table>
  	<table class="listeLegere">
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libelle</th>
                <th class="montant">Montant</th>                
             </tr>
<?php          
            // demande de la requête pour obtenir la liste des éléments hors
            // forfait du visiteur connecté pour le mois demandé
            $req = obtenirReqEltsHorsForfaitFicheFrais2();
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
    </table>
    <p> 
        <input class="bouton" onClick="aaa()" type="reset" value="Valider les frais" size="21"/>
        <input class="bouton" onClick="bbb()" type="reset" value="Refuser les frais" size="21"/>
    </p>
  </div>
<?php
        }
    }
?>    
  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?> 
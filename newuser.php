<?php
/** 
 * Script de contrôle et d'affichage du cas d'utilisation "Saisir fiche de frais"
 * @package default
 * @todo  RAS
 */
  $repInclude = './include/';
  require($repInclude . "_init.inc.php");

  // page inaccessible si visiteur non connecté
  if (!estVisiteurConnecte()) {
      header("Location: cSeConnecter.php");  
  }
  require($repInclude . "_entete.inc.html");
  require($repInclude . "_sommaire.inc.php");
  // affectation du mois courant pour la saisie des fiches de frais
  $mois = sprintf("%04d%02d", date("Y"), date("m"));
  // vérification de l'existence de la fiche de frais pour ce mois courant
  $existeFicheFrais = existeFicheFrais($idConnexion, $mois, obtenirIdUserConnecte());
  // si elle n'existe pas, on la crée avec les élets frais forfaitisés à 0
  if ( !$existeFicheFrais ) {
      ajouterFicheFrais($idConnexion, $mois, obtenirIdUserConnecte());
  }
  // acquisition des données entrées
  // acquisition de l'étape du traitement 
  $etape=lireDonnee("etape","demanderSaisie");
  // acquisition des quantités des éléments forfaitisés 
  $tabQteEltsForfait=lireDonneePost("txtEltsForfait", "");
  // acquisition des données d'une nouvelle ligne hors forfait
  $idLigneHF = lireDonnee("idLigneHF", "");
  $dateHF = lireDonnee("txtDateHF", "");
  $libelleHF = lireDonnee("txtLibelleHF", "");
  $montantHF = lireDonnee("txtMontantHF", "");
 
  // structure de décision sur les différentes étapes du cas d'utilisation
  if ($etape == "validerSaisie") { 
      // l'utilisateur valide les éléments forfaitisés         
      // vérification des quantités des éléments forfaitisés
      $ok = verifierEntiersPositifs($tabQteEltsForfait);      
      if (!$ok) {
          ajouterErreur($tabErreurs, "Chaque quantité doit être renseignée et numérique positive.");
      }
      else { // mise à jour des quantités des éléments forfaitisés
          modifierEltsForfait($idConnexion, $mois, obtenirIdUserConnecte(),$tabQteEltsForfait);
      }
  }                                                       
  elseif ($etape == "validerSuppressionLigneHF") {
      supprimerLigneHF($idConnexion, $idLigneHF);
  }
  elseif ($etape == "validerAjoutLigneHF") {
      verifierLigneFraisHF($dateHF, $libelleHF, $montantHF, $tabErreurs);
      if ( nbErreurs($tabErreurs) == 0 ) {
          // la nouvelle ligne ligne doit être ajoutée dans la base de données
          ajouterLigneHF($idConnexion, $mois, obtenirIdUserConnecte(), $dateHF, $libelleHF, $montantHF);
      }
  }
  else { // on ne fait rien, étape non prévue 
  
  }                                  
?>
  <!-- Division principale -->
  <div id="contenu">
      <h2>Ajouter un utilisateur</h2>
 		<fieldset>
            <legend>Nouvel utilisateur
            </legend>
            <p>
              <label for="id">* Id : </label>
              <input type="text" id="id" name="id" size="5" maxlength="10" 
                     title="Entrer un id" 
                      />
            </p><p>
              <label for="nom">* Nom : </label>
              <input type="text" id="nom" name="nom" size="20" maxlength="10" 
                     title="Entrer un nom" 
                      />
            </p><p>
              <label for="prenom">* Prenom : </label>
              <input type="text" id="prenom" name="prenom" size="20" maxlength="10" 
                     title="Entrer un prenom" 
                      />
            </p><p>
              <label for="login">* Login : </label>
              <input type="text" id="login" name="login" size="10" maxlength="10" 
                     title="Entrer un login" 
                      />
            </p><p>
              <label for="mdp">* Mot de passe : </label>
              <input type="password" id="mdp" name="mdp" size="20" maxlength="10" 
                     title="Entrer un mdp" 
                      />
            </p><p>
              <label for="adresse">* Adresse : </label>
              <input type="text" id="adresse" name="adresse" size="50" maxlength="10" 
                     title="Entrer un adresse" 
                      />
            </p><p>
              <label for="cp">* Code Postal : </label>
              <input type="text" id="cp" name="cp" size="5" maxlength="10" 
                     title="Entrer un cp" 
                      />
            </p><p>
              <label for="ville">* Ville : </label>
              <input type="text" id="ville" name="ville" size="20" maxlength="10" 
                     title="Entrer un ville" 
                      />
            </p><p>
              <label for="dateEmbauche">* Date embauche : </label>
              <input type="date" id="dateEmbauche" name="dateEmbauche" size="5" maxlength="10" 
                     title="Entrer un dateEmbauche" 
                      />
            </p><p>
              <label for="metier">* Metier : </label>
              <input type="text" id="metier" name="metier" size="15" maxlength="10" 
                     title="Entrer un metier" 
                      />
            </p>
            
         </fieldset>
      <div class="piedForm">
      <p>
        <input id="ajouter" type="submit" value="Ajouter" size="20" title="Ajouter user" />
      </p> 
      </div>

  </div>
<?php        
  require($repInclude . "_pied.inc.html");
  require($repInclude . "_fin.inc.php");
?> 
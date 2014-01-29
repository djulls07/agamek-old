<?php
if (isset($_SESSION['user']) && !empty($_SESSION['user']) && $_SESSION["user"] != null)
  //membre connecté on le redirige.
  header('Location: ?page=accueil');
?>

<?php
$affiche = true;
if (isset($_POST['envoi'])) {
  $_POST['dateNaissance'] = htmlspecialchars($_POST['dateNaissance']);
  if (regex_inscription()) {
    if (checkdate($_POST['mois_naissance'], $_POST['jour_naissance'], $_POST['annee_naissance'])) {
      $_POST['dateNaissance'] = $_POST['annee_naissance'].'-'.$_POST['mois_naissance'].'-'.$_POST['jour_naissance'];
    } else {
      $_POST['dateNaissance'] = '0000-00-00';
    }
    //on creer l'objet membre
    //var_dump($_POST);
			
    $tab_jeux = traitement_liste_jeux();
    $membre = new Membre($_POST, 'infos/infos_bdd');
    //on a traité la liste des jeux, on l'ajoute au Membre
			
    $liste_jeu = explode(";", file_get_contents("infos/liste_jeux"));
    foreach($liste_jeu as $key=>$val) {
      if (isset($tab_jeux[$val])) {
	$membre->ajout_jeu($val, $tab_jeux[$val]);
      }
    }
			
    //var_dump($membre);
    if (!$membre->existe()) {
      try {
	$membre->ecrire_bdd();
	$membre->activation_compte($_SESSION['server']['domain'].'/?page=activate&h=',
				   $_SESSION['server']['name'],$_SESSION['server']['domain']);
	echo '<p>'.$_SESSION['text']['inscription_ok'].'</p>';
	$affiche = false;
      } catch (Exception $e) {
	echo 'Error : ' . $e->getMessage();
      }
    } else {
      $affiche = false;
      echo '<p>'.$_SESSION['text']['compte_existe'].'</p>';
      echo '<meta http-equiv="refresh" content="5; URL=?page=accueil"/>';
    }
			
			
  }
}
	
if ($affiche) {
  ?>

  <form action="?page=inscription" method="post">
    <h2> <?=$_SESSION['text']['inscription_titre']?> </h2>
    <div class="champs_formulaire">
    <label for="pseudo">Pseudo </label>
			  <?php echo '<input type="text" size="25" name="pseudo" value="'.$_POST['pseudo'].'"/>'; ?>
    <span id="pseudo"></span>
			  </div>
				
			  <div class="champs_formulaire">
			  <label for="mdp">Mot de passe </label>
					     <input type="password" size="25" name="mdp" value="<?=$_POST['mdp']?>"/>
					     <span id="mdp"></span>
					     </div>
				
					     <div class="champs_formulaire">
					     <label for="mdpp">Again </label>
								 <input type="password" size="25" name="mdpp" value="<?=$_POST['mdpp']?>"/>
								 <span id="mdpp"></span>
								 </div>
				
								 <div class="champs_formulaire">
								 <label for="email">Email </label>
										      <?php echo '<input type="text" size="25" name="email" value="'.$_POST['email'].'"/>'; ?> 
    <span id="email"></span>
										      </div>
				
										      <div class="champs_formulaire">
										      <label>Date de naissance </label>
										      <select name="jour_naissance">
										      <?php 
										      for ($i=1;$i<32;$i++) { 
											echo '<option ';
											if ($_POST['jour_naissance'] == $i) 
											  echo 'selected>';
											else echo '>';
											echo $i.'</option>';
										      }?>
  </select> 
      <select name="mois_naissance">
      <?php 
      for ($i=1;$i<13;$i++) { 
	echo '<option ';
	if ($_POST['mois_naissance'] == $i) 
	  echo 'selected>';
	else echo '>';
	echo $i.'</option>';
      }?>
  </select>
      <select name="annee_naissance">
      <?php 
      for ($i=date("Y");$i>1950;$i--) { 
	echo '<option ';
	if ($_POST['annee_naissance'] == $i) 
	  echo 'selected>';
	else echo '>';
	echo $i.'</option>';
      }?>
  </select> 
      </div>
				
      <div class="champs_formulaire">
      <label for="localisation">Country </label>
				  <?php echo '<input type="text" name="localisation" value="'.$_POST['localisation'].'"/>'; ?>
      <span id="localisation"></span>
				  </div>
				
				  <div class="champs_formulaire">
				  <label for="sexe">Genre </label>
						      <?php 
						      if ($_POST['sexe'] == "masculin") {
							echo '<input type="radio" name="sexe" value="masculin" checked/>Masculin';
							echo '<input type="radio" name="sexe" value="feminin" />Feminin';
						      } else if ($_POST['sexe'] == "feminin") {
							echo '<input type="radio" name="sexe" value="masculin" />Masculin';
							echo '<input type="radio" name="sexe" value="femicnin" checked />Féminin';
						      } else {
							?>
							<input type="radio" name="sexe" value="masculin" />Masculin
							  <input type="radio" name="sexe" value="feminin" />Feminin
							  <?php
							  }
  ?>
  </div>
				
      <div class="champs_formulaire">
      <label for"nom">Nom </label>
			<?php echo '<input type="text" name="nom" value="'.$_POST['nom'].'"/>'; ?>
      <span id="nom"></span>
			</div>
				
			<div class="champs_formulaire">
			<label for="prenom">Prenom </label>
					      <?php echo '<input type="text" name="prenom" value="'.$_POST['prenom'].'"/>'; ?>
      <span id="prenom"></span>
					      </div>
				
					      <div class="champs_formulaire">
					      <label>Un petit commentaire sur toi</label>
					      <?php echo '<textarea name="bio">' . $_POST['bio'] . '</textarea>'; ?>
      </div>
				
					      <div class="champs_formulaire">
					      <label for="signature"> Signature </label>
									<?php echo '<input type="text" size="25" name="signature" value="'.$_POST['signature'].'"/>'; ?>
      <span id="signature"></span>
									</div>
				
									<div class="champs_formulaire">
									<label for="metier">Metier </label>
											      <?php echo '<input type="text" size="25" name="metier" value="'.$_POST['metier'].'"/>'; ?>
      <span id="metier"></span>
											      </div>
				
											      <div id="liste_jeux" class="champs_formulaire">
											      <label> Jeux: </label> <br />
											      <?php affiche_liste_jeux(); ?>
      </div>	
				
											      <div class="champs_formulaire">
											      <input type="checkbox" name="disclaimer"/> J'ai lu et accepté les <a href="">Conditions générales du site</a>
				</div>
				
				<div class="champs_formulaire">
						<input name="envoi" type="submit" value="Envoi"/>
				</div>	
				
		</form>

	<!-- 
	Partie js pour appel ajax et verif tps reel.
	-->
	<script type="text/javascript">
		var xhr = null;
		
		function request(callback, name, value) {
			xhr = getXMLHttpRequest();
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					//ici la requete ajax est terminée on peut l'utiliser
											      callback(xhr.responseText);
}
};
			
xhr.open("POST",'src_php/xhrReq/xhrListeReqInscription.php',true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			
// ajaxload
document.getElementById(name).innerHTML = '<img class="ajaxload" src="images/ajaxload/ajax-loader.gif" title="loading"/>';
			
			     var str = "req=verif_"+name+"&"+name+"="+value;
			
			     if (name == "mdp") {
			       str += "&mdpp="+document.getElementsByName("mdpp")[0].value;
			     }
			     else if (name == "mdpp") {
			       str += "&mdp="+document.getElementsByName("mdp")[0].value;
			     }

xhr.send(str);
}
		
//fonction qui affiche ce que renvoi la requete ajax !
function readData(str) {
  //alert(str);
  var obj = JSON.parse(str);
  for (node in obj) {
    if (obj[node] == "ok")
      document.getElementById(node).innerHTML = '<img class="ajaxok" src="images/ajaxload/check-ok.png" title="ok"/>';
    else
      document.getElementById(node).innerHTML = obj[node];
    //il faut ajouter des spans a coté de input pour y ecrire le message erreur ou le ok pr chaque champs
  }
}
		
// on ajoute l'appel de la fonction request aux differents champs input, et on l'appel avec le name et la value
// au moment de la perte de focus du champs pour verif temps reeel !!!
		
(function() {
  var inputs = document.getElementsByTagName("input");
  for(var i=0; i<inputs.length; i++) {
    inputs[i].onchange = function() {
      request(readData, this.name, this.value);
    };
  }
})();
</script>
<?php
}
?>
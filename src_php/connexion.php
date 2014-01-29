<?php
session_start();

echo '<div id="connexion">';
if (isset($_POST['envoi_co']) && isset($_POST['pseud']) && !empty($_POST['pseud']) && isset($_POST['passw']) && !empty($_POST['passw'])) {
  $_POST['pseud'] = htmlspecialchars($_POST['pseud']);
  $_POST['passw'] = htmlspecialchars($_POST['passw']);
  // appel bdd verif hash et loging
  try {
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $p = "infos/infos_bdd";
    if (file_exists($p)) {
      $tmp = json_decode(file_get_contents($p), true);
    }
    $bdd = new PDO("mysql:host=" . $tmp['host'] . ";dbname=" . $tmp['name'], $tmp['user'], $tmp['password'],$pdo_options);
    $h = sha1($_POST['pseud'].$_POST['passw']);
    $r = $bdd->query("SELECT * FROM users WHERE _hash=\"".$h."\"");
    if ($d = $r->fetch()) {
      if ($d['_actif'] == 1) {
	//on log in
	$membre = new Membre($d, $p);
	$membre->majListeJeuxBdd($p);
	$_SESSION['user'] = serialize($membre);
      } else {
	$_SESSION['text']['compte_n_actif'];
      }
    } else {
      //pas de compte a connecté.
      $err = $_SESSION['text']['compte_n_existe'];
    }
    //$r->closeCursor();
  } catch (Exception $e) {
    echo 'Erreur: ' . $e->getMessage();
  }
}

if (isset($_SESSION['user']) && $_SESSION["user"] != null && !empty($_SESSION["user"])) {// le membre est connecté
  echo "<div><a href=\"?page=deconnexion\">".$_SESSION['text']['deco'].".</a>
	</div><div><a href=\"?page=profil\">".$_SESSION['text']['profil']."</a></div>";
  echo '<p>' . $_SESSION['text']['connecte_ok'] . ' ' . unserialize($_SESSION['user'])->getPseudo() . '</p>';
	
} else {
  echo '<div><a href="?page=inscription"><span class="plus"><img src="http://www.dota2.cz/files/playdota2_favicon.ico"></span>'.$_SESSION['text']['inscription'].'</a></div>';
  echo '<div id="connect">';
  echo '</div>';
  ?>
  <form id="form_co" method="POST" action="#">

     <fieldset>
		
     <legend>Connexion</legend>

     <label for="pseud">Pseudo</label>

			  <input type="text" size="16" id="pseud" name="pseud"/>

			  <label for="passw">Mot de passe</label>

					       <input type="password" id="passw" name="passw"/><br />
		
					       <input type="submit" id="sub_co" name="envoi_co" value="connexion"/> <br /><br />
		
					       <?=$err?>
		
					       </fieldset>
					       </form>

					       <?php
					       }
echo '</div>';
?>
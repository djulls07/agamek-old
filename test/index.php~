<?php
/*curl -X POST 'http://api.sc2ranks.com/v2/characters/search' 
-d 'name=djulls07&bracket=1v1&expansion=hots&rank_region=global&api_key=eOMywLAr9CoZVwVxlLPbprp0pH6ogOR8DNyE'


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://api.sc2ranks.com/v2/characters/search');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$data = array('name' => 'shanini_151@hotmail.com', 'bracket' => '1v1', 'expansion' => 'hots',
				'rank_region' => 'global', 'api_key' => 'eOMywLAr9CoZVwVxlLPbprp0pH6ogOR8DNyE'); 
				
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$output = curl_exec($ch);
curl_close($ch);

print $output;
echo
		'<script type="text/javascript">
			var val;
			val = prompt("Quel est votre region ? ( EU, US, KR )", val);
			alert(val);
		</script>';


include 'classes/Membre.class.php';
//verification pseudo
function verif_pseudo($tab) {
	if (isset($tab["pseudo"]) && !empty($tab["pseudo"])) {
		//verification du champs pseudo
		if (!preg_match("#^[a-zA-Z0-9._-]{2,25}$#", $tab["pseudo"])) {
			echo '{"pseudo":"mauvais format"}';
		}
		else {
			try {
				$p = 'infos/infos_bdd';
				if (file_exists($p))
					$tmp = json_decode(file_get_contents($p), true);
				else {
					echo '{"pseudo":"error file infos bdd"}';
					return;
				}
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO('mysql:host='.$tmp['host'].';dbname='.$tmp['name'],
								$tmp['user'], $tmp['password'], $pdo_options);
				
				$rep = $bdd->query("SELECT * FROM users WHERE _pseudo=\"".$tab['pseudo']."\"");
				if ($rep->fetch()) {
					echo '{"pseudo":"not free"}';
				}
				else {
					echo '{"pseudo":"ok"}';
				}
				$rep->closeCursor();
			} catch (Exception $e) {
				echo '{"pseudo":"error DataBase : ' . $e->getMessage().'"}';
			}
		}
	}
	else {
		echo '{"pseudo":"champs a remplir"}';
	}
}
$_tab = array("pseudo" => "djulls", "nom" => "Leg");
foreach($_tab as $key => $value) {
	$_tab['_'.$key] = $value;
}
print_r($_tab);
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
        <title>Site de la mort qui... nait !</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
<?php
//TODO: Tournoi sc2 ce soir !
session_start();

$tps_lim = 1384527662; 

function recup_l_j() {
	$p = 'listejoueur';
	$res = array();
	$fd = fopen($p,'r');
	if ($fd) {
		while(!feof($fd)) {
			$buf = fgets($fd);
			if (strlen($buf) < 8) break;
			$tmp_a = explode(';',$buf);
			$res[$tmp_a[0]]['level'] = $tmp_a[1];
			$res[$tmp_a[0]]['race'] = $tmp_a[2];
		}
		fclose($fd);
	}
	return $res;
}

//on recup liste des joueurs !
$tab_joueur = recup_l_j();
//print_r($tab_joueur);


if (isset($_GET['envoi_form']) && (time() < $tps_lim)) {
	// Traitement formulaire.
	$test = true;
	//on verifie si le joueur existe deja.
	$p = 'listejoueur';
	
	if (!empty($_GET['pseudo'])) {
		$str = htmlspecialchars($_GET['pseudo']) . ';' . $_GET['level'] . ';' . $_GET['race'] . "\n";
		foreach($tab_joueur as $key=>$value) {
			if (strcmp($key, $_GET['pseudo']) == 0) {
				$test = false;
				echo '<p> Votre pseudo est deja enregistre pour ce tournoi Redirection dans 3 sec !</p>';
				echo '<meta http-equiv="refresh" content="3;URL=index.php"/>';
			}
		}
		if ($test) {
			while(!file_put_contents($p, $str, FILE_APPEND | LOCK_EX))
				continue;
			echo '<p> Vous avez bien ete enregistre, redirection dans 3 sec !</p>';
			echo '<meta http-equiv="refresh" content="3;URL=index.php"/>';
		}
	} else {
		echo '<p> Vous devez remplir les champs <br />';
		echo '<meta http-equiv="refresh" content="3;URL=index.php"/>';
	}
}
else {
	//on affiche la liste des joueurs
	echo '<div> <h3> Liste participants: </h3> <br />'; 
	$i=1;
	foreach($tab_joueur as $k=>$v) {
		echo $i++ .'. ' . $k . ', ' . $tab_joueur[$k]['race'] . ', ' . strtoupper($tab_joueur[$k]['level']) . '<br />';
	}
	echo '</div>';
}
if (time() < $tps_lim) {
?>
<div id="formulaire">
<h3> S'inscrire au tournoi: </h3>
<form  action="index.php" method="GET">
	<input type="text" name="pseudo"/>
	<SELECT name="level">
		<option value="bronze"> Bronze </option>
		<option value="argent"> Argent </option>
		<option value="or"> Or </option>
		<option value="platine"> Platine </option>
		<option value="diamand"> Diamand </option>
		<option value="master"> Master </option>
		<option value="gmaster"> Grd Master </option>
	</SELECT>
	<SELECT name="race">
		<option value="zerg"> Zerg </option>
		<option value="terran"> Terran </option>
		<option value="protoss"> Les plus beaux </option>
	</SELECT>
	<input type="submit" name="envoi_form" value="Envoyer"/>
</form>
</div>
<?php
}
?>
</body>
</html>
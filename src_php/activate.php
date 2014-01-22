<?php
session_start();
/** 
 * Ici on active le compte ssi les infos sont ok et qu'il n'est pas deja activé !
 *
**/
if (isset($_GET['h']) && !empty($_GET['h'])) {
	//on check la bdd si account existe et pas activé, on active (passer actif a 1).
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$path = 'infos/infos_bdd';
	
	if (file_exists($path))
		$tmp = json_decode(file_get_contents($path), true);
	else
		echo 'Error: Cant connect to the DataBase, please retry later.';
	
	//on verifie l'existence du hash dans la bdd
	try {
		$bdd = new PDO('mysql:host='.$tmp["host"].';dbname='.$tmp['name'],
						$tmp['user'], $tmp['password'],$pdo_options);
		$rep = $bdd->query("SELECT * FROM users WHERE _hash=\"".$_GET['h']."\"");
		if ( $data = $rep->fetch() ) {
			if ($data['_actif'] == 0) {
				$bdd->exec("UPDATE users SET _actif=1 WHERE _hash=\"".$_GET['h']."\"");
				$membre = new Membre($data, 'infos/infos_bdd');
				$membre->majListeJeuxBdd();
				$_SESSION['user'] = serialize($membre);
				echo '<p> '.$_SESSION['text']['compte_active'].' </p>';
				echo '<meta http-equiv="refresh" content="5;URL=?page=accueil"/>';
			} else {
				echo '<p>'.$_SESSION['text']['actif_deja'].'</p>';
				echo '<meta http-equiv="refresh" content="5;URL=?page=accueil"/>';
			}
		}
		$rep->closeCursor();
	} catch (Exception $e) {
		echo 'Error DATABASE: ' . $e->getMessage();
	}
	
} else {
	header('Location: http://www.google.com');
}

?>
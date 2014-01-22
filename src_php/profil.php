<?php

//il faut etre log pour voir le profil de qqn ou meme le sien !
if (!is_log()) {
	header('Location: ?page=accueil');
}
else {
	if (!isset($_GET['profil']) || empty($_GET['profil'])) {
		//ici on affiche le profil du mec connecté, -1 correspond a personne donc lui meme.
		//on regarde le chache, si ressent on l'affiche, sinon on affiche avec membre ( comme co, a jour )
		$membre = unserialize($_SESSION['user']);
		$cache = "cache/profil/".$membre->getPseudo().'.html';
		if (file_exists($cache) && filemtime($cache) > time() - 3600) {
			readfile($cache);
		} else {
			ob_start();
			$membre->afficheProfil();
			$page = ob_get_contents();
			ob_end_clean();
			file_put_contents($cache, $page);
			echo $page;
		}
	} else {
		//on affiche le profil de qqn d'autre, dc sans les options de changement etc etc.
		//appel bdd etc etc pr afficher profil ou le prendre en cache.
		$cache = "cache/profil/".$_GET['profil'];
		if (file_exists($cache) && filemtime($cache) > time() - 3600) {
			readfile($cache);
		} else {
			$p = "infos/infos_bdd";
			$tmp = json_decode(file_get_contents($p), true);
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO("mysql:host=".$tmp['host'].";dbname=".$tmp['name'], $tmp['user'], $tmp['password'], $pdo_options);
			//TODO: UN NEW CONSTRUCTEUR POUR CREEER OBJET MEMBRE DEPUIS BDD AVEC LISTE JEU ETC ETC
			//TODO: A USE DANS PR contruire le membre au moment de la connexion... on need seulement pseudo/hash
			$req = "SELECT * FROM user WHERE pseudo=\"".$_GET['profil']."\"";
			$rep = $bdd->query($req);
			if ($d=$rep->fetch()) {
				$membre = new Membre($_GET['pseudo']); //TODO: creer ce constructeur imba ! ( cas ou deja dans bdd a verif tjr ).
				ob_start();
				$membre->afficheProfil();
				$page = ob_get_contents();
				ob_end_clean();
				file_put_contents($cache, $page);
				echo $page;	
			} else {
				echo $_SESSION['text']['no_profil'];
			}
		}
	}
	 
}
?>
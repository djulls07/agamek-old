<?php

function regex_inscription() {
	echo '<div id="erreurs_inscription">';
	$test=true;
	
	//DETERMINER SI OBLIGATOIRE ?!
	if (isset($_POST["metier"])) {
		$_POST['metier'] = htmlspecialchars($_POST['metier']);
		if (!preg_match("#^[a-zA-Z-_ ]{0,255}$#", $_POST['metier'])) {
			echo '<p> Metier: Format inccorect, utilisez char.</p>';
			$test = false;
		}
	}
	
	if (isset($_POST["signature"])) {
		$_POST['signature'] = htmlspecialchars($_POST['signature']);
		if (!preg_match("#^[a-zA-Z-_]{0,25}$#", $_POST['signature'])) {
			echo '<p> Signature: Format inccorect, utilisez uniquement des lettres (25 max).</p>';
			$test = false;
		}
	}
	
	//verif pseudo
	$_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
 	if (!isset($_POST['pseudo']) OR empty($_POST['pseudo'])) {
 		echo '<p> Element manquant : pseudo </p>';
 		$test=false;
 	}
 	else if (!preg_match("#^[a-z0-9.-_]{2,25}$#",$_POST['pseudo'])) {
 		$test=false;
 		echo '<p> Pseudo doit contenir lettres et chiffres et entre 2 et 25 caracteres</p>';
 	}
 	
	//-----Verif mail------
 	$_POST['email'] = htmlspecialchars($_POST['email']);
 	if (!isset($_POST['email']) OR empty($_POST['email'])) {
 		echo '<p> Element manquant : e-mail </p>';
 		$test=false;
 	}
 	else if (!preg_match("#^[a-z0-9.-_]+@[a-z]{2,}\.[a-z]{2,4}$#",$_POST['email'])) {
 		$test=false;
 		echo '<p> Format e-mail incorrect, format autorise : nobody-_.bonjour@nous.com</p>';
 	}
 	
 	//-----Verif mdp------
 	$_POST['mdp'] = htmlspecialchars($_POST['mdp']);
 	if (!isset($_POST['mdp']) OR empty($_POST['mdp'])) {
 		echo '<p> Element manquant : mot de passe </p>';
 		$test=false;
 	}
 	else if (!preg_match("#^[a-z0-9-._]{5,255}$#",$_POST['mdp'])) {
 		$test=false;
 		echo '<p> Format mot de passe inccorect, seulement lettres et chiffres autorisés et taille minimum : 5 caractères.</p>';
 	}
 	
 	//-----Verif mdpp------
 	if (!isset($_POST['mdpp']) OR empty($_POST['mdpp'])) {
 		echo '<p> Element manquant : confirmation mot de passe </p>';
 		$test=false;
 	}
 	else if (!preg_match("#^[a-z0-9]{5,255}$#",$_POST['mdpp'])) {
 		$test=false;
 		echo '<p> Format confirmation mot de passe inccorect, seulement lettres et chiffres autorisés et taille minimum : 5 caractères.</p>';
 	}
 	//les deux mdp doivent correspondre : 
 	if ($_POST['mdp'] != $_POST['mdpp']) {
 		echo '<p> les deux mots de passe doivent etre identiques.</p>';
 		$test=false;
 	}
 	
 	//----------verif du reste!----------
 	if (isset($_POST['nom']) AND !empty($_POST['nom'])) {
 		$_POST['nom'] = htmlspecialchars($_POST['nom']);
 		if (!preg_match("#^[a-zA-Z]{1}[a-zA-Zéè\\\']{2,255}$#",$_POST['nom'])) {
 			$test=false;
 			echo '<p> format nom invalide, seule les lettres sont autorisées ! </p>';
 		}
 	}
 	 	
 	if (isset($_POST['prenom']) AND !empty($_POST['prenom'])) {
 		$_POST['prenom'] = htmlspecialchars($_POST['prenom']);
 		if (!preg_match("#^[a-zA-Z]{1}[a-zA-Zéè\\\']{2,255}$#",$_POST['prenom'])) {
 			$test=false;
 			 echo '<p> format prenom invalide, seule les lettres sont autorisées !</p>';
 		}
 	}

 	if (isset($_POST['localisation']) AND !empty($_POST['localisation'])) {
 		$_POST['localisation'] = htmlspecialchars($_POST['localisation']);
 		if (!preg_match("#^[a-zA-Z0-9-_ ]{2,255}$#", $_POST['localisation'])) {
 			$test=false;
 			echo '<p> format localisation invalide, seule les lettres/chiffres sont autorises.</p>';
 		}
 	}
 	else {
		$test = false;
		echo '<p> Element manquant: localisation </p>';
 	}
 	
 	if (isset($_POST['sexe']) AND !empty($_POST['sexe'])) {
 		$_POST['sexe'] =htmlspecialchars($_POST['sexe']);
 		if (!preg_match("#^[a-zA-Z]{1}[a-zA-Zéè\\\']{2,255}$#",$_POST['sexe'])) {
 			$test=false;
 			 echo '<p> format sexe invalide, seule les lettres sont autorisées !</p>';
 		}
 	}
	else {
		$test = false;
		echo '<p> Element manquant: sexe </p>';
 	}
 	
 	if (isset($_POST['bio']) AND !empty($_POST['bio'])) {
 		$_POST['bio'] = htmlspecialchars($_POST['bio']);
 	}
 	
 	if (isset($_POST['disclaimer']) AND !empty($_POST['disclaimer'])) {
 		$_POST['disclaimer'] = htmlspecialchars($_POST['disclaimer']);
 	}
 	else {
		$test = false;
		echo '<p> Vous devez lire et accepter les conditions generales pour vous inscrire. </p>';
 	}
 	echo '</div>';
 	return $test;
 }
 
 function affiche_liste_jeux() {
	
	/*[MOBA] : Dota 2 / LoL 
[RTS] : Starcraft 2 / Warcraft 3
[FPS] : Counter Strike* / Quake / BF* / CoD* / Shootmania
[MMO] : WoW / FF 14 ARR / TESO / Neverwinter / SWTOR / GW2 / TERA / DCUO / RIFT
[VFight] : Street Fighter4* / UMvC* / KOF* / Tekken* / Soul Calibur*
[Other] : Fifa* / Add une case texte */

InfoJeuSC2::affiche_form_infos();
InfoJeuDota2::affiche_form_infos();
InfoJeuLoL::affiche_form_infos();

}

function traitement_liste_jeux() {
	//TODO: retourne un tableau d'objet infojeu, tab['sc2'] = new InfoJeuSC2(param);
	
	//STARCRAFT2
	$_POST['pseudo_sc2'] = htmlspecialchars($_POST['pseudo_sc2']);
	if (isset($_POST['pseudo_sc2']) && !empty($_POST['pseudo_sc2'])) {
		$tab_info_jeux['sc2'] = new InfoJeuSC2($_POST['pseudo_sc2'], $_POST['region_sc2']);
		//$tab_info_jeux['sc2']->recup_infos_api();
	}
	
	//DOTA2
	$_POST['pseudo_dota2'] = htmlspecialchars($_POST['pseudo_dota2']);
	if (isset($_POST['pseudo_dota2']) && !empty($_POST['pseudo_dota2'])) {
		$tab_info_jeux['dota2'] = new InfoJeuDota2($_POST['pseudo_dota2']);
	}
	
	//LOL
	$_POST['pseudo_lol'] = htmlspecialchars($_POST['pseudo_lol']);
	if (isset($_POST['pseudo_lol']) && !empty($_POST['pseudo_lol']) && isset($_POST['region_lol'])) {
		$tab_info_jeux['lol'] = new InfoJeuLoL($_POST['pseudo_lol'], $_POST['region_lol']);
	}
	
	return $tab_info_jeux;
}

//user is loggin or not ?
function is_log() {
	if (isset($_SESSION['user']) && !empty($_SESSION['user']))
		return true;
	else
		return false;
}

?>
<?php

/**
 * Classe membre.
 * Il s'agit de l'objet qui contient toutes les infos concernant
 * un membre inscrit du site !!
 *
**/
	class Membre {																
	
		/** Informations perso générales **/
		private $_pseudo;
		private $_avatar;
		private $_dateNaissance;
		private $_email;
		private $_actif;
		private $_dateInscription;
		private $_sexe;
		private $_nom;
		private $_prenom;
		private $_bio;
		private $_signature;
		private $_metier;
		private $_localisation;
		private $_hash;
		private $_id;
		
		/** Informations perso lié au site **/
		private $_nbCommentaire;
		private $_listeAmis;
		private $_nbAmis;
		private $_droits; // a revoir, heritage, faire un membre admin un redac...
		
		/** Informations lié au jeu 
		 *  Un tableau qui contient nom_jeu => ObjJeu.
		**/
		private $_listeJeux;
		
		/** Informations concernant la bdd.**/
		private $_bddDest;
		private $_bddUser;
		private $_bddPass;
		
		
		/* pour le consruire on lui passe un tableau qui contient toutes
		 * les elements necessaire.
		 * lors de l'inscription il suffira d'envoyer le tableau POST par exemple.
		 * et lors de la connexion on enverra le tab de donnees renvoyer par appel sql a la bdd
		 * pour le niveauAcces, nbamis et nbcomment, il faut voir avec comment on sorganise.
		 */
		
		
		/** Constructeur et destructeur **/
		
		/** Celui qui permet de creer objet m a partir inscription et ou activation_compte **/
		public function __construct($_tab, $path) {
			/**
			 * On recupere les parametre de la bdd dans ../info_bdd/infos
			**/
			
			/** 
			*  On change le nom de clé nom en _nom pour que le constructeur 
			*  marche avec les champs de la bdd immediatement.
			**/
			
			foreach($_tab as $key => $value) {
				$nkey = substr($key,1,strlen($key)-1);
				$_tab[$nkey] = $_tab[$key];
			}
			
			try {
				if (file_exists($path)) {
					$tmp = file_get_contents($path);
					$tmp2 = json_decode($tmp,true);
					$tmp2['dest'] = 'mysql:host=' . $tmp2['host'] . ';dbname=' . $tmp2["name"];
					$this->_bddUser = $tmp2['user'];
					$this->_bddDest = $tmp2['dest'];
					$this->_bddPass = $tmp2['password'];
					
				} else {
					echo 'Error construct: infos DB missing';
					break;
				}
			} catch (Exception $e) {
				echo 'Error construct' . $e->getMessage();
			}
			
			//pseudo
			try {
				$this->_pseudo = $_tab["pseudo"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			//hash pseudo.passe
			try {
				$this->_hash = sha1($_tab['pseudo'].$_tab['mdp']);
			} catch (Exception $e) {
				die ('Error: ' . $e->getMessage());
			}
			//avatar
			try {
				$this->_avatar = $_tab["avatar"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			//date, ici il faut convertir Jour/Mois/année en sec. A faire avt ecrire bdd.
			//ici on considere que c'est fait.
			try {
				$this->_dateNaissance = $_tab["dateNaissance"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_actif = $_tab["actif"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_email = $_tab["email"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_dateInscription = $_tab["dateInscription"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_sexe = $_tab["sexe"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_nom = $_tab["nom"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_prenom = $_tab["prenom"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_bio = $_tab["bio"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_signature = $_tab["signature"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_metier = $_tab["metier"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_droits = 0;
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
			
			try {
				$this->_localisation = $_tab["localisation"];
			}
			catch (Exception $e) {
				die ("Erreur" . $e->getMessage());
			}
		}
		
		
		public function __destruct() {
			//destructeur membre.
		}
		
		
		/** Setters & Getters**/
		public function getPseudo() {
			return $this->_pseudo;
		}
		
		/** Autre methodes importante !**/
		public function ecrire_bdd() {
			try {
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO($this->_bddDest, $this->_bddUser, $this->_bddPass,$pdo_options);
				$req_sql = "INSERT INTO users(_pseudo,_hash,_email, _avatar,
				_dateNaissance,_localisation,_sexe,_nom,_prenom,_bio,_droits,
				_metier,_signature,_dateInscription) 
				VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,CURDATE())";
				
				$req = $bdd->prepare($req_sql);
			
				$req->execute(array($this->_pseudo,$this->_hash,$this->_email,
					$this->_avatar,$this->_dateNaissance,$this->_localisation,
					$this->_sexe,$this->_nom,$this->_prenom,$this->_bio,
					$this->_droits,$this->_metier,$this->_signature));
				
				//ajout des infos jeux a la bdd
				$id_user = $bdd->lastInsertId();
				foreach($this->_listeJeux as $k=>$v) {
					$this->_listeJeux[$k]->ecrire_bdd($id_user);
				}
			} catch (Exception $e) {
				echo 'Error PDO: ' . $e->getMessage();
			}
		}
			
		public function activation_compte($addr_activation, $nom_site, $domaine) {
			//nothing yet
			
			//choix du retour a la ligne... ( les normes...).
			$hash = $this->_hash;
			$mail = $this->_email;
			
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
			{
				$n = "\r\n";
			}
			else
			{
				$n = "\n";
			}
				//=====Déclaration des messages au format texte et au format HTML.
			$message_txt = 'Click here: '.$addr_activation.$hash.' to activate your account on ' . $nom_site . '.' . $n;
			
			$message_html = '<html><head></head><body>
							Click <a href="'.$addr_activation.$hash.'"> On this LINK </a> 
							to activate your account on ' . $nom_site . '<br />' . '
							</body></html>';
			
			//==========
			
			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			
			//=====Définition du sujet.
			$sujet = "ACTIVATION COMPTE" . strtoupper($nom_site).$n;
			//=========
			
			//=====Création du header de l'e-mail.
			$header = 'From: "'.$nom_site.'" <contact@'.$domaine.'> ' . $n;
			$header.= 'Reply-to: "'.$nom_site.'" <contact@'.$domaine.'>'.$n;
			$header.= "MIME-Version: 1.0".$n;
			$header.= "Content-Type: multipart/alternative;".$n." boundary=\"".$boundary."\"".$n;
			//==========
			
			//=====Création du message.
			$message = $n.'--'.$boundary.$n;
			//=====Ajout du message au format texte.
			$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$n;
			$message.= "Content-Transfer-Encoding: 8bit".$n;
			$message.= $n.$message_txt.$n;
			//==========
			$message.= $n."--".$boundary.$n;
			//=====Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$n;
			$message.= "Content-Transfer-Encoding: 8bit".$n;
			$message.= $n.$message_html.$n;
			//==========
			$message.= $n."--".$boundary."--".$n;
			$message.= $n."--".$boundary."--".$n;
			//==========
			
			//=====Envoi de l'e-mail.
			mail($mail,$sujet,$message,$header);
			
			//==========
		}
		
		public function existe() {
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO($this->_bddDest, $this->_bddUser, $this->_bddPass,$pdo_options);
			$rep = $bdd->query('SELECT _email from users WHERE _email="'.$this->_email.'" ');
			return $rep->fetch();
			$bdd->closeCursor();
		}
		
		public function ajout_jeu($key, $obj) {
			$this->_listeJeux[$key] = $obj;
		}
		
		/** Celui qui met a jour le membre ( jeux ) **/
		public function majListeJeuxBdd() {
			try {
				if (file_exists($path)) {
					$tmp = file_get_contents($path);
					$tmp2 = json_decode($tmp,true);
					$tmp2['dest'] = 'mysql:host=' . $tmp2['host'] . ';dbname=' . $tmp2["name"];
					$this->_bddUser = $tmp2['user'];
					$this->_bddDest = $tmp2['dest'];
					$this->_bddPass = $tmp2['password'];
				} else {
					echo 'Error construct: infos DB missing';
				}
				//ici on a la bdd
				$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
				$bdd = new PDO($this->_bddDest, $this->_bddUser, $this->_bddPass, $pdo_options);
				$req = 'SELECT * FROM infos_jeux WHERE id_joueur='.$this->_id;
				$rep = $bdd->query($req);
				while($d=$rep->fetch()) {
					switch($d->id_jeu) {
						case 1: 
							$this->_listeJeux['sc2'] =  new InfoJeuSC2($d['pseudo'], $d['region']);
							break;
						case 2:
							$this->_listeJeux['dota2'] = new InfoJeuDota2($d['pseudo']);
							break;
						case 3:
							$this->_listeJeux['lol'] = new InfoJeuLoL($d['pseudo'], $d['region']);
							break;
						default:
							break;
					}
					$new_r = 'InfoJeu'.$new;
					
				}
			} catch (Exception $e) {
				echo 'Error construct' . $e->getMessage();
			}
		}
	}

?>
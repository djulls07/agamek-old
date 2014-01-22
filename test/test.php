<?php
/*
require_once ('../dota2-api-master/config.php');
		$players_mapper_web = new players_mapper_web();
		$players_info = $players_mapper_web->add_id(76561197971339843)->load();
		foreach($players_info as $player_info) {
			echo $player_info->get('realname');
			echo '<img src="'.$player_info->get('avatarfull').'" alt="'.$player_info->get('personaname').'" />';
			echo '<a href="'.$player_info->get('profileurl').'">'.$player_info->get('personaname').'\'s steam profile</a>';
		}
		//print_r($players_info);
		*/
		
		
function activation_compte($addr_activation, $nom_site, $domaine) {
			//nothing yet
			$mail = 'djulls07@gmail.Com';
			//choix du retour a la ligne... ( les normes...).
			
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail))
			{
				$n = "\r\n";
			}
			else
			{
				$n = "\n";
			}
				//=====Déclaration des messages au format texte et au format HTML.
			$message_txt = $n;
			
			$message_html = '<html><head></head><body>
							Click <a href="'.$addr_activation.$hash.'"> On this LINK </a> 
							to activate your account on ' . $nom_site . '<br />' . '
							
							<form action="adeptus.webatu.com" method="GET">
							<input name="pseudo" type="text"/>
							<input type="submit" value="envoyer" name="envoi"/>
							</form>
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
		
		activation_compte("djulls07@gmail.com", 'adeptus.webatu.com', 'adeptus', 'webatu.com');
	
	?>
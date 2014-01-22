<?php 
session_start();
/*function my_autoloader($class) {
    include 'classes/' . $class . '.class.php';
}

spl_autoload_register('my_autoloader');*/
include_once('classes/InfoJeu.class.php');
include_once("classes/Membre.class.php");

/**
 * QQ fonctions indispensable au bon fonctionnement, recup infos bdd, langues etc...
 *
**/

//ici on rempli le tableau avec les infos connexion bdd etc...
if (!isset($_SESSION['server']) || empty($_SESSION['server'])) {
	$p = 'infos/infos_server';
	if (file_exists($p)) {
		$_SESSION['server'] = json_decode(file_get_contents($p), true);
	}
}

//ici on rempli le tab de session avec une langue ou une autre.. ici fr defaut
if (!isset($_SESSION['text']) || empty($_SESSION['text'])) {
	$pa = 'langues/fr';//TODO: comment on choisi ce fic, eng, fr etc etc...
	if (file_exists($pa)) {
		$_SESSION['text'] = json_decode(file_get_contents($pa), true);
	}
}

include_once("src_php/fonctions.php");
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
    <head>
        <title>Site de la mort qui... nait !</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="style/architecture.css" media="screen">
    </head>
	<body>
	<script type="text/javascript" src="javascript/fonctions.js"> </script>
	<?php
        
        // Les 2 barres top (position:fixed).
        include_once("src_php/head.php");
		
		//lecteur ?!
		//include_once('src_php/lecteur.php');		
		
		// La page centrale
		/// position : absolute avecu un margin-top    ?>	
	<div class="page">
		<div class="contentgauche">
			<?php for ($i=0;$i<200;$i++) printf("pub "); ?>
		</div>
	
		<div class="contentcentre">
			<div class="banniere">
				<?php include_once("src_php/banniere.php"); ?>
			</div>
					
			<div class="content" id="centre">
			<?php 
				// GET de la page à afficher
				if (!isset($_GET['page']) || empty($_GET["page"])) {
					$_GET["page"] = 'accueil';
				}
				
				include_once("src_php/" . $_GET["page"] . '.php'); 
			?>
			</div>
		</div>
		
		<div class="contentdroite">
			<?php for ($i=0;$i<250;$i++) printf("pub "); ?>
		</div>
	</div>

	
	<div class="foot">
			<?php include_once("src_php/footer.php"); ?>
	</div>
		
    </body>
</html>

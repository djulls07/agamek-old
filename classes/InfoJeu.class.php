<?php

/**
 *	Ceci est une classe qui permet de stocker les niveau des joueurs dans les diff jeux..
 * On peut faire heriter un obj par Jeu/ type Jeu pour stocker ces informations.
 * class abstraite pr methode communes et meth a definir selon le jeu ( recup info ou les afficher ).
 **/

abstract class InfoJeu {
	
  abstract protected static function affiche_form_infos();
  abstract protected function recup_infos_api();
  abstract protected function ecrire_bdd($id_user);
  abstract protected function afficheDonnees();
  abstract protected function getElement($key);
  abstract protected function afficheResume();
	
}

/** 
 * Ici on creer les differentes class pour infojeu ( sc2, dota ) qui auront des
 * methode differentes pour recup et afficher les info ( surtout dans le futur ).
 **/


/**------------------------------- CLASS SC2 -------------------------------**/
class InfoJeuSC2 extends InfoJeu {
	
  private $_bracket;
  private $_expansion;
  private $_api_key;
  private $_pseudo;
  private $_region;
  private $_donnees = NULL;
  public $_id_jeu = 1;
	
  public function __construct($pseudo, $region) {
    $this->_pseudo = $pseudo;
    $this->_region = $region;
    $this->_expansion = "hots";
    $this->_api_key = 'eOMywLAr9CoZVwVxlLPbprp0pH6ogOR8DNyE';
  }
	
  //methode a lance si le client s'inscrit en cochant sc2.
  public static function affiche_form_infos() {
    echo 
      '	<div id="form_sc2">
			<label for="pseudo_sc2"> Pseudo Starcraft 2: </label>
			<input name="pseudo_sc2" type="text"/>
			<label for="region_sc2"> Region </label>
			<SELECT name="region_sc2"></div>
		';
    //European
    if (isset($_POST['region_sc2']) && $_POST['region_sc2'] == "eu")
      echo '<OPTION value="eu" selected> European </OPTION>';
    else
      echo '<OPTION value="eu"> European </OPTION>';
    //Coree
    if (isset($_POST['region_sc2']) && $_POST['region_sc2'] == "KR")
      echo '<OPTION value="KR" selected> Korea </OPTION>';
    else
      echo '<OPTION value="KR"> Korea </OPTION>';
    //USA
    if (isset($_POST['region_sc2']) && $_POST['region_sc2'] == "US")
      echo '<OPTION value="US" selected> America </OPTION>';
    else
      echo '<OPTION value="US"> America </OPTION>';
					
    echo '</SELECT>';
    /*echo '<label for="bracket"> Bracket </label>';
      echo '<SELECT name=bracket>';
      //1v1
      if (isset($_POST['bracket']) && $_POST['bracket'] == "1v1")
      echo '<OPTION value="1v1" selected> 1v1 </OPTION>';
      else
      echo '<OPTION value="1v1"> 1v1 </OPTION>';
				
      //2v2
      if (isset($_POST['bracket']) && $_POST['bracket'] == "2v2")
      echo '<OPTION value="2v2" selected> 2v2 </OPTION>';
      else
      echo '<OPTION value="2v2"> 2v2 </OPTION>';
					
      //3v3
      if (isset($_POST['bracket']) && $_POST['bracket'] == "3v3")
      echo '<OPTION value="3v3" selected> 3v3 </OPTION>';
      else
      echo '<OPTION value="3v3"> 3v3 </OPTION>';
					
      //3v3
      if (isset($_POST['bracket']) && $_POST['bracket'] == "4v4")
      echo '<OPTION value="4v4" selected> 4v4 </OPTION>';
      else
      echo '<OPTION value="4v4"> 4v4 </OPTION>';
			
      echo '</SELECT>';*/
  }
	
  public function recup_infos_api() {
    $b = "1v1";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://api.sc2ranks.com/v2/characters/search');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    $data = array('name' => $this->_pseudo, 'bracket' => $b, 'expansion' => $this->_expansion,
		  'rank_region' => "global", 'api_key' => $this->_api_key);
						
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    $this->_donnees = $output;
  }
	
  public function ecrire_bdd($id_user) {
    $p = "infos/infos_bdd";
    try {
      if (file_exists($p)) {
	$tab = json_decode(file_get_contents($p),true);
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO("mysql:host=".$tab['host'].";dbname=".$tab['name'], $tab['user'], $tab['password'], $pdo_options);
	$req = $bdd->prepare("INSERT INTO infos_jeux(id_joueur, id_jeu, pseudo, region) VALUES(".$id_user.",?,?,?)");
	$req->execute(array($this->_id_jeu, $this->_pseudo, $this->_region));
      }
    } catch(Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }

  public function afficheDonnees()
  {
    if ($this->_donnees == NULL)
      $this->recup_infos_api();
    echo $this->_donnees;
  }

  public function getElement($key) {
    return $this->$key;
  }

  public function afficheResume()
  {
    echo $_SESSION['text']['profil'] . 'Starcraft 2 ( SC2 ) : ';
    echo '<br />';
    echo $_SESSION['text']['pseudo'] . ' : '. $this->_pseudo;
    echo '<br />';
    echo $_SESSION['text']['region'] . ' : ' . $this->_region;
  }
}


/**------------------------------ CLASS DOTA2 ------------------------------ **/

class InfoJeuDota2 extends InfoJeu {

  private $_steamId64;
  private $_pseudo;
  private $_dotaId;
  private $_steamId;
  private $_id_jeu = 2;
  private $_donnees = NULL;
    
  /**
   *abstract protected function recup_infos_api();
   *abstract protected function ecrire_bdd($id_user);*/
	
  public function __construct($pseudo) {
	
    $this->_pseudo = $pseudo;
		
    //recup le steamId et SteamId64 a partir du nom du compte
    $userAgent = 'DeathGame SITE ( social geek network )';

    // Create the initial link you want.
    $target_url = 'http://steamidconverter.com/' . $pseudo;

    // Initialize curl and following options
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
    curl_setopt($ch, CURLOPT_URL,$target_url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		
    // Grab the html from the page
    $html = curl_exec($ch);
    curl_close($ch);
		
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html); //load le html dans le domdoc
    libxml_clear_errors();
		
    $this->_steamId64 = $dom->getElementById('steamID64')->nodeValue;
    $this->_steamId = $dom->getElementById('steamID')->nodeValue;
    $this->_dotaId = self::convert_id_dota($dom->getElementById('steamID64')->nodeValue);
  }
	
  //converti un steamid64 en dota2Id ou le contraire !
  public static function convert_id_dota($id) {
    if (strlen($id) === 17) {
      $converted = substr($id, 3) - 61197960265728;
    }
    else {
      $converted = '765'.($id + 61197960265728);
    }
    return (string) $converted;
  }
	
  public static function affiche_form_infos() {
    echo '<div id="form_dota2" ><label for="pseudo_dota2"> pseudo Dota 2 (steam): </label>
			<input name="pseudo_dota2" type="text"/></div>
			';
  }
	
  public function ecrire_bdd($id_user) {
    $p = "infos/infos_bdd";
    try {
      if (file_exists($p)) {
	$tab = json_decode(file_get_contents($p),true);
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO("mysql:host=".$tab['host'].";dbname=".$tab['name'], $tab['user'], $tab['password'], $pdo_options);
	$req = $bdd->prepare("INSERT INTO infos_jeux(id_joueur, id_jeu, pseudo, steamID, steamID64, dotaID) VALUES(".$id_user.",?,?,?,?,?)");
	$req->execute(array($this->_id_jeu, $this->_pseudo, $this->_steamId, $this->_steamId64, $this->_dotaId));
      }
    } catch(Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }
	
  public function recup_infos_api() 
  {
    //on recup les stats du player ( league niveau, 
    //stats par heroes pr mobalike)etc..
  }
  
  public function afficheDonnees() 
  {
    if ($this->_donnees == NULL)
      $this->recup_infos_api();
    echo $this->donnees;
  }

  public function getElement($key)
  {
    return $this->$key;
  }

  public function afficheResume()
  {
    echo $_SESSION['text']['profil'] .
      'DOTA 2 ( Defense Of The Ancients ) : <br />';
    echo $_SESSION['text']['pseudo'] . ' : ' . $this->_pseudo . '<br />';
  }
}

/* ----------------------------------- LOL -------------------------------- */
class InfoJeuLoL extends InfoJeu {
	
  private $_pseudo;
  private $_region;
  private $_donnees = NULL;
  private $_id_jeu = 3;
  private static $_personalRatings = "personal_ratings";
  private static $_resSearch = "search_result_item";
  private static $_lifeTimeStats = "lifetime_stats";
	
  public function __construct($pseudo, $region) {
    $this->_pseudo = $pseudo;
    $this->_region = $region;
  }
	
  public static function affiche_form_infos() {
		
    echo '<div id="form_lol" ><label for="pseudo_lol"> pseudo League of Legends: </label>
			<input name="pseudo_lol" type="text"/>
			<label for="region_lol">region League of Legends:</label>
			<SELECT name="region_lol"></div>
		';
    //European
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "euw")
      echo '<OPTION value="euw" selected> Eu West </OPTION>';
    else
      echo '<OPTION value="euw"> Eu West</OPTION>';
    //Eu EN
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "eune")
      echo '<OPTION value="eune" selected> Eu Nordic/East </OPTION>';
    else
      echo '<OPTION value="eune"> Eu Nordic/East </OPTION>';
				
    //USA
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "na")
      echo '<OPTION value="na" selected> North America </OPTION>';
    else
      echo '<OPTION value="na"> North America </OPTION>';
				
    //Braziou
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "br")
      echo '<OPTION value="br" selected> Brasil </OPTION>';
    else
      echo '<OPTION value="br"> Brasil </OPTION>';
					
    //Turquie
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "tr")
      echo '<OPTION value="tr" selected> Turkey </OPTION>';
    else
      echo '<OPTION value="tr"> Turkey </OPTION>';
					
    //Russia
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "ru")
      echo '<OPTION value="ru" selected> Russia </OPTION>';
    else
      echo '<OPTION value="ru"> Russia </OPTION>';
					
    //LAN
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "lan")
      echo '<OPTION value="lan" selected> Latin America North </OPTION>';
    else
      echo '<OPTION value="lan"> Latin America North </OPTION>';
				
    //LAS
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "las")
      echo '<OPTION value="las" selected> Latin America South </OPTION>';
    else
      echo '<OPTION value="las"> Latin America South </OPTION>';
				
    //Oceania
    if (isset($_POST['region_lol']) && $_POST['region_lol'] == "oce")
      echo '<OPTION value="oce" selected> Oceania </OPTION>';
    else
      echo '<OPTION value="oce"> Oceania </OPTION>';
					
    echo '</SELECT>';
  }
	
  public function ecrire_bdd($id_user) {
    $p = "infos/infos_bdd";
    try {
      if (file_exists($p)) {
	$tab = json_decode(file_get_contents($p),true);
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO("mysql:host=".$tab['host'].";dbname=".$tab['name'], $tab['user'], $tab['password'], $pdo_options);
	$req = $bdd->prepare("INSERT INTO infos_jeux(id_joueur, id_jeu, pseudo, region) VALUES(".$id_user.",?,?,?)");
	$req->execute(array($this->_id_jeu, $this->_pseudo, $this->_region));
      }
    } catch(Exception $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }
	
  public function recup_infos_api()
  {
    $userAgent = 'DeathGame SITE ( social geek network )';

    // Create the initial link you want.
    $target_url = 'http://www.lolking.net/search?name=loulex&region=euw';

    // Initialize curl and following options
    $ch = curl_init($target_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    //permet de follow links sans changer config PHP serveur.
    $output = curl_exec_follow($ch);
    curl_close($ch);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($output);
    libxml_clear_errors();
    
    /** On a trouve le summoner, on peut afficher sa page lolking et y chercher
     ** ce qu'on veut.
     **/

    //ici on demande une rep mais on pourrai en avoir +ieurs.
    $tmp = getElementsByClassName("div", "search_result_item", $dom, 9, "onclick");

    //on cherche la bonne region dans les results
    foreach($tmp as $k => $v) {
      if (preg_match("/".$this->_region."/", $v)) {
	$tmp[0] = $v;
	break;
      }
    }
    $tmp[0] = substr($tmp[0], 18, -16);

    //echo 'tmp0 : ' . $tmp[0];

    $target_url = 'http://lolking.net/' . $tmp[0];
    $ch = curl_init($target_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //recup page lolking statistiques & personal ratings
    $html = curl_exec_follow($ch);

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html); //load le html dans le domdoc
    libxml_clear_errors();
    
    //personal ratings
    $tmp = array();
    $tmp = getElementsByClassName("ul", "personal_ratings", $dom, 1, null);
    
    //lifetime stats
    $tmp2 = array();
    $tmp2 = getElementsByClassName("div", "lifetime_stats", $dom, 1, null);

    echo $tmp[0] . "<br />" . $tmp2[0]; 
  }



  public function afficheDonnees()
  {
    if ($this->_donnees == NULL)
      $this->recup_infos_api();
    
    echo $this->_donnees;
  }

  public function getElement($key)
  {
    return $this->$key;
  }

  public function afficheResume()
  {
    echo $_SESSION['text']['profil'] . 'League of Legends ( LoL ) : <br />';
    echo $_SESSION['text']['pseudo'] . ' : ' . $this->_pseudo . '<br />';
    echo $_SESSION['text']['region'] . ' : ' . $this->_region;
  }
}
?>
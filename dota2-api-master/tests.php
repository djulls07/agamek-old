<?php
/*
function convert_id($id) {
        if (strlen($id) === 17) {
            $converted = substr($id, 3) - 61197960265728;
        }
        else {
            $converted = '765'.($id + 61197960265728);
        }
        return (string) $converted;
    }
    
require_once ('config.php');
$players_mapper_web = new players_mapper_web();
$players_info = $players_mapper_web->add_id('76561197971339843')->load();
foreach($players_info as $player_info) {
    echo $player_info->get('realname');
    echo '<img src="'.$player_info->get('avatarfull').'" alt="'.$player_info->get('personaname').'" />';
    echo '<a href="'.$player_info->get('profileurl').'">'.$player_info->get('personaname').'\'s steam profile</a>';
}
print_r($players_info);*/


$userAgent = 'DeathGame SITE';

		// Create the initial link you want.
		$target_url = 'http://steamidconverter.com/aomey';

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
		//echo $html;
		
		$dom = new DOMDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($html); //load le html dans le domdoc
		libxml_clear_errors();
		
		$e = $dom->getElementById('steamID64')->nodeValue;
		echo $e;
		//echo $dom->getElementById('steamID');
?>
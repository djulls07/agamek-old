<?php
function scan_rep($path_mp3) {
	try {
		$tab = array();
		$myDir = opendir($path_mp3);
		while (($file = readdir($myDir))) {
			if ($file=="." || $file == "..") continue; //on ignore les cas dossier parent et dossier courant.
			if (is_dir($path_mp3.'/'.$file)) scan_rep($path_mp3.'/'.$file);
			else {
				//echo 'file : '.$file;
				array_push($tab, $path_mp3.'/'.$file);
			}	
		}
		closedir($myDir);
		return $tab;
	} catch(Exception $e) {
		die ('erreur' . $e->getMessage());
	}
}

$path_mp3 = 'sons';

$mp3 = scan_rep($path_mp3);
array_push($mp3, 'http://broadcast.infomaniak.net/radionova-high.mp3');

 echo '<audio id="audio" controls style="visibility:hidden;">
 		<source src="'.$mp3[array_rand($mp3)].'"/>
 	</audio>';
	
?>
<script type="text/javascript">
	document.onkeypress = function() {
		if (event.keyCode == 178) {
			if (document.getElementById("audio").style.visibility == "visible") {
				document.getElementById("audio").style.visibility = "hidden";
			}
			else {
				document.getElementById("audio").style.visibility = "visible";
			}
		}
	};
</script>
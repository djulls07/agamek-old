<?php
$path = "infos/infos_bdd";
$time = 1; //TODO:REMETTRE 3600 apres periode test...
//il faut etre log pour voir le profil de qqn ou meme le sien !
if (!is_log()) {
  header('Location: ?page=accueil');
}

if (empty($_GET['profil']) && $_GET["page"] == "profil") {
  $membre = unserialize($_SESSION['user']);
  $cache = "cache/profil_priv/".$membre->getPseudo().'.html';
  if (file_exists($cache) && filemtime($cache) > time() - $time) {
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
  //on affiche le profil de qqn d'autre, dc sans les options
  //de changement etc etc.
  //appel bdd etc etc pr afficher profil ou le prendre en cache.

  $cache = "cache/profil_pub/".$_GET['profil'].".html";
  if (file_exists($cache) && filemtime($cache) > time() - $time) {
    readfile($cache);
  } else {
    $p = "infos/infos_bdd";
    $membre = Membre::createMembre($_GET['profil'], $p);
    if ($membre == NULL) {
      echo $_SESSION['text']['no_profil'];
    } else {
      ob_start();
      $membre->afficheProfil();
      $page = ob_get_contents();
      ob_end_clean();
      file_put_contents($cache, $page);
      echo $page;
    }
  }
}
?>
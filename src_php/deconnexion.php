<?php
session_start();
session_destroy();
session_unset();
header('Location: http://adeptus.webatu.com?page=accueil');
?>
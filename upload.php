<?php
session_start();
if ( !isset($_SESSION['u']) || empty($_SESSION['u']) ) {
  header('Location: index.php');
  exit();
}


?>


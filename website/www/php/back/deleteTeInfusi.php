<?php
  require_once("website/www/php/back/session.php");
  require_once("website/www/php/back/DBAccess.php");
  require_once("website/www/php/back/Image.php");

  if($_SESSION['tipo_utente'] != 'Admin'){
    header('Location: website/www/php/front/redirect.php?error=3');
    exit;
  }

  $con = new DBAccess();
  $img = new Image();
  $id = $_GET['id'];

  if($con->openConnection()) {
    if($con->deleteTeInfusi($id)) {
  	   $img->deleteImage("website/www/img/te_e_infusi/".$id."jpg");
     }
     else {
       header('Location: website/www/php/front/redirect.php?error=4');
       exit;
     }
   } // end if $con->openConnection()
   else {
     header('Location: website/www/php/front/redirect.php?error=1');
     exit;
   }

  header('Location: website/www/php/front/modifica_teInfusi.php');
  exit;
?>

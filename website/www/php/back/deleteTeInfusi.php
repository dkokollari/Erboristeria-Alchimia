<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("Image.php");

  if($_SESSION['tipo_utente'] != 'Admin'){
    header('Location: ../front/redirect.php?error=3');
    exit;
  }

  $con = new DBAccess();
  $img = new Image();
  $id = $_GET['id'];

  if($con->openConnection()) {
    if($con->deleteTeInfusi($id)) {
  	   $img->deleteImage("../../img/te_e_infusi/".$id."jpg");
     }
     else {
       header('Location: ../front/redirect.php?error=4');
       exit;
     }
   } // end if $con->openConnection()
   else {
     header('Location: ../front/redirect.php?error=1');
     exit;
   }

  header('Location: ../front/modifica_teInfusi.php');
  exit;
?>

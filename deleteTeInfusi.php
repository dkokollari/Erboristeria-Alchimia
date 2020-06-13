<?php
  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  $img = new Image();
  $id = $_GET['id'];

  if($con->openConnection()) {
    if($con->deleteTeInfusi_by_id($id)) {
  	   $img->deleteImage("img/te_e_infusi/".$id."jpg");
     }
     else {
       header('Location: redirect.php?error=4');
       exit;
     }
   } // end if $con->openConnection()
   else {
     header('Location: redirect.php?error=1');
     exit;
   }

  header("Refresh:1; url=modifica_teInfusi.php");
  exit;
?>

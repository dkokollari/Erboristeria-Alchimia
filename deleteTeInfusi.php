<?php
  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  $img = new Image();
  $id = $_GET['id'];

  if($con->openConnection()){
    if($con->deleteTeInfusi_by_id($id)){
  	   $img->deleteImage("img/te_e_infusi/".$id."jpg");
       echo "query esequita con successo";
     }
     else{
       echo "query non eseguita";
     }
   }
   else{
     echo "Operazione non eseguita riprova dopo";
   }

  header("Refresh:1; url=modifica_teInfusi.php");
  exit;
?>
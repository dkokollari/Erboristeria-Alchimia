<?php
require_once("DBAccess.php");
require_once("Image.php");
require_once("control_input.php");

$con = new DBAccess();
if($con->openConnection()){
  $pagina = file_get_contents('inserimento_teinfusi.html');

  $messaggio = "";
  $valnome = "";
  $valingre = "";
  $valdescr = "";
  $valprepa = "";
  $valdescImg = "";
  $messaggio ="";
  $errNome ="";
  $errDescr ="";
  $errImg="";
  $errDescImg="";
  $errori=0;

//se è stato premuto il buttone submit
if(isset($_POST['submit'])){
      $tipo = $_POST['Tipo'];

      //CONTROLLO input
      $nome = control_input::name_control($_POST['Nome']);
      $ingre = control_input::control($_POST['Ingredienti']);
      $descr = control_input::description_control($_POST['Descrizione']);
      $prepa = control_input::control($_POST['Preparazione']);
      if(!$nome){
        $errori++;
        $errNome='<span class="error-msg">deve contenere solo caratteri: a-z A-Z 0-9 - _, con lunghezza minima 5 e massima 50</span>';
      }
      if(!$descr){
        $errori++;
        $errDescr='<span class="error-msg">deve contenere almeno 20 caratteri e non pi&ugrave; di 50 caratteri</span>';
      }

      $image = new Image();
      $imgpresent= false;
      //controllo se un'immagine è stata caricata
      if(is_uploaded_file($_FILES['immagine']['tmp_name'])){
        $imgpresent = true;
        $descImg = control_input::description_control($_POST['desc_img']);
        if(!$descImg){
          $errori++;
          $errDescImg = '<span class="error-msg">deve contenere almeno 20 caratteri e non pi&ugrave; di 50 caratteri</span>';
        }
        $errImg = $image->isValid($_FILES['immagine']['name']);
        if($errImg!=""){
          $errori++;
          $pagina = str_replace("%ERR_IMG%", $errImg, $pagina);
        }
      }

      //se non ci sono errori
      if($errori==0){
        if($imgpresent && $con->InsertTeInfusi($descImg,$tipo,$nome,$ingre,$descr,$prepa)){
          $id = $con->getId($nome);
          if($image->uploadImageTeInfusi($_FILES['immagine']['name'],$_FILES['immagine']['tmp_name'],$id)){
            $messaggio = "";
          } else {
            $messaggio = '<p class="error-msg">Errore: immagine non salvata</p>';
            $con->deleteTeInfusi($nome);
          }
        } else if(!$imgpresent && $con->InsertTeInfusi($descImg,$tipo,$nome,$ingre,$descr,$prepa)){
          $messaggio = "";
        } else {
          $messaggio = '<p class="error-msg">Query non eseguita riprovare pi&ugrave; tardi</p>';
        }
      }
      else {
        $messaggio = '<p class="error-msg">Errore: ci sono '. $errori.' errori</p>';
        $valnome = $_POST['Nome'];
        $valingre = $_POST['Ingredienti'];
        $valdescr = $_POST['Descrizione'];
        $valprepa = $_POST['Preparazione'];
        $valdescImg = $_POST['desc_img'];
      }
}

  $pagina = str_replace("%nome%",$valnome , $pagina);
  $pagina = str_replace("%ingre%",$valingre , $pagina);
  $pagina = str_replace("%descr%",$valdescr , $pagina);
  $pagina = str_replace("%prepa%",$valprepa, $pagina);
  $pagina = str_replace("%imgdes%",$valdescImg , $pagina);
  $pagina = str_replace("%MESSAGGIO%", $messaggio, $pagina);
  $pagina = str_replace("%ERR_NOME%", $errNome, $pagina);
  $pagina = str_replace("%ERR_DESC%", $errDescr, $pagina);
  $pagina = str_replace("%ERR_IMG%", $errImg, $pagina);
  $pagina = str_replace("%ERR_IMGDESC%", $errDescImg, $pagina);

  echo $pagina;
}
else{
  echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
  exit;
}

?>

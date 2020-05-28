<?php
  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  if($con->openConnection()){
    $pagina = file_get_contents('inserimento_teinfusi.html');

    $errori=0;
    $imgpresent= false;
    $tipo = $_POST['Tipo'];
    $nome = trim($_POST['Nome']);
    $ingre = trim($_POST['Ingredienti']);
    $descr = trim($_POST['Descrizione']);
    $prepa = trim($_POST['Preparazione']);
    $image = new Image();

    //se è stato premuto il buttone submit
    if(isset($_POST['submit'])){
      //controllo se l'immagine è stata caricata
      if(is_uploaded_file($_FILES['immagine']['tmp_name'])){
        $imgpresent = true;
        $descImg = trim($_POST['desc_img']);
        $errDescImg = validInput($descImg,"Descrizione immagine");
        if($errDescImg!=""){
          $errori++;
        }
        $errImg = $image->isValid($_FILES['immagine']['name']);
        if($errImg!=""){
          $errori++;
        }
      }

      //CONTROLLO input
      $errNome = validInput($nome,"Nome");
      $errDescr = validInput($descr,"Descrizione");
      if($errNome!=""){
        $errori++;
      }
      if($errDescr!=""){
        $errori++;
      }

      //se non ci sono errori
      if($errori==0){
        if($imgpresent && $con->InsertTeInfusi($descImg, $tipo, $nome, $ingre, $descr, $prepa)){
          $id = $con->getId($nome);
          if($image->uploadImageTeInfusi($_FILES['immagine']['name'], $_FILES['immagine']['tmp_name'], $id)){
            $messaggio = "";
          }
          else{
            $messaggio = '<p class="error-msg">Errore: immagine non salvata</p>';
            $con->deleteTeInfusi($nome);
          }
        }
        else if(!$imgpresent && $con->InsertTeInfusi($descImg, $tipo, $nome, $ingre, $descr, $prepa)){
          $messaggio = "";
        }
        else{
          $messaggio = '<p class="error-msg">Query non eseguita riprovare pi&ugrave; tardi</p>';
        }
      } //endif se non ci sono errori
      else{
        $messaggio = '<p class="error-msg">Errore: ci sono '. $errori.' errori</p>';
        $valnome = $_POST['Nome'];
        $valingre = $_POST['Ingredienti'];
        $valdescr = $_POST['Descrizione'];
        $valprepa = $_POST['Preparazione'];
        $valdescImg = $_POST['desc_img'];
      }
    } //endif se è stato premuto il buttone submit

    $pagina = str_replace("%action%","inserimento_teinfusi.php", $pagina);
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

  function validInput($name, $description){
    return ($name!="" ? "" : '<small class="error-msg">Inserisci '.$description.'</small>');
  }
?>

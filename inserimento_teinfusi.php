<?php
  require_once("DBAccess.php");
  require_once("Image.php");
  require_once("control_input.php");

  $con = new DBAccess();
  if($con->openConnection()) {
    $pagina = file_get_contents('inserimento_teinfusi.html');

    $messaggio = "";
    $valnome = "";
    $valingre = "";
    $valdescr = "";
    $valprepa = "";
    $valdescImg = "";
    $errDescImg= "";
    $messaggio = "";
    $errNome = "";
    $errDescr = "";
    $errImg= "";
    $errori = 0;

    if(isset($_GET['id'])){  // se è una modifica
      $id = $_GET['id'];
      $getElement = $con->getSingoloTeInfuso($id);
      $valtipo = htmlentities($getElement['Tipo']);
      $valnome = htmlentities($getElement['Nome']);
      $valingre = htmlentities($getElement['Ingredienti']);
      $valdescr = htmlentities($getElement['Descrizione']);
      $valprepa = htmlentities($getElement['Preparazione']);
      $valdescImg = htmlentities($getElement['desc_img']);
    }

    // se è stato premuto il buttone submit
    if(isset($_POST['submit'])) {
      $tipo = $_POST['Tipo'];
      // CONTROLLO input
      $nome = control_input::name_control($_POST['Nome']);
      $ingre = control_input::control($_POST['Ingredienti']);
      $descr = control_input::description_control($_POST['Descrizione']);
      $prepa = control_input::control($_POST['Preparazione']);
      if(empty($nome)) {
        $errori++;
        $errNome = 'non deve contenere caratteri speciali, con lunghezza minima 5 e massima 50';
      }
      if(empty($descr)) {
        $errori++;
        $errDescr = 'deve contenere almeno 20 caratteri e non pi&ugrave; di 50 caratteri';
      }

      $image = new Image();
      $imgpresent = false;
      // controllo se un'immagine è stata caricata
      if(is_uploaded_file($_FILES['immagine']['tmp_name'])) {
        $imgpresent = true;
        $descImg = control_input::description_control($_POST['desc_img']);
        if(!$descImg) {
          $errori++;
          $errDescImg = 'deve contenere almeno 20 caratteri e non pi&ugrave; di 50 caratteri';
        }
        $errImg = $image->isValid($_FILES['immagine']['name']);
        if($errImg!="") {
          $errori++;
          $pagina = str_replace("%ERR_IMG%", $errImg, $pagina);
        }
      }

      // se non ci sono errori
      if($errori==0) {

         if(isset($_GET['id'])){  // e una modifica
           if(!$con->updateTeInfusi($id, $nome, $tipo, $ingre, $descr, $prepa, $descImg)) {
             header('Location: redirect.php?error=4');  //query non eseguita
             exit;
           }
         }
         else if(!$con->insertTeInfusi($descImg, $tipo, $nome, $ingre, $descr, $prepa)){
           header('Location: redirect.php?error=4');   //query non eseguita
           exit;
         }else {
           $id = $con->getId_TeInfusi($nome);  // non è una modifica e è stato eseguita la query quindi aggiorno id con nuovo valore
         }

         if($imgpresent){ // se è presente l'immagine la carichiamo
             $image->uploadImageTeInfusi($_FILES['immagine']['name'], $_FILES['immagine']['tmp_name'], $id));
             header('Location: teinfusi.php');
             exit;
           }

      } //endif se non ci sono errori
      else {
        $messaggio = '<p class="error-msg">Errore: ci sono '.$errori.' errori</p>';
        $valnome = $_POST['Nome'];
        $valingre = $_POST['Ingredienti'];
        $valdescr = $_POST['Descrizione'];
        $valprepa = $_POST['Preparazione'];
        $valdescImg = $_POST['desc_img'];
      }
    } //endif se è stato premuto il buttone submit

    $pagina = str_replace("%action%","inserimento_teinfusi.php", $pagina);
    $pagina = str_replace("%nome%", $valnome, $pagina);
    $pagina = str_replace("%ingre%", htmlentities($valingre), $pagina);
    $pagina = str_replace("%descr%", $valdescr, $pagina);
    $pagina = str_replace("%prepa%", $valprepa, $pagina);
    $pagina = str_replace("%imgdes%", $valdescImg, $pagina);
    $pagina = str_replace("%MESSAGGIO%", $messaggio, $pagina);
    $pagina = str_replace("%ERR_NOME%", $errNome, $pagina);
    $pagina = str_replace("%ERR_DESC%", $errDescr, $pagina);
    $pagina = str_replace("%ERR_IMG%", $errImg, $pagina);
    $pagina = str_replace("%ERR_IMGDESC%", $errDescImg, $pagina);

    echo $pagina;
  }
  else {
    header('Location: redirect.php?error=1');
    exit;
  }
?>

<?php
  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  if(!$con->openConnection()) {
    header('Location: redirect.php?error=1');
    exit;
  }
  else {
    $pagina = file_get_contents("../html/inserimento_teinfusi.html");

    $id = $_GET['id'];
    $getElement = $con->getSingolo_TeInfusi($id);
    $valdescr = htmlentities($getElement[0]['descrizione_immagine_te_e_infusi']);
    $valtipo = htmlentities($getElement[0]['tipo_te_e_infusi']);
    $valnome = htmlentities($getElement[0]['nome_te_e_infusi']);
    $valingre = htmlentities($getElement[0]['ingredienti_te_e_infusi']);
    $valdescImg = htmlentities($getElement[0]['descrizione_te_e_infusi']);
    $valprepa = htmlentities($getElement[0]['preparazione_te_e_infusi']);

    // se è stato premuto il buttone submit
    if(isset($_POST['submit'])) {
      $errori = 0;
      $imgpresent = false;
      $tipo = $_POST['Tipo'];
      $nome = trim($_POST['Nome']);
      $ingre = trim($_POST['Ingredienti']);
      $descr = trim($_POST['Descrizione']);
      $prepa = trim($_POST['Preparazione']);
      $image = new Image();

      // controllo se l'immagine è stata caricata
      if(is_uploaded_file($_FILES['immagine']['tmp_name'])) {
        $imgpresent = true;
        $descImg = trim($_POST['desc_img']);
        $errDescImg = validInput($descImg,"Descrizione immagine");
        if($errDescImg != "")
          $errori++;
        $errImg = $image->isValid($_FILES['immagine']['name']);
        if($errImg != "")
          $errori++;
      }

      // CONTROLLO input
      $errNome = validInput($nome, "Nome");
      $errDescr = validInput($descr, "Descrizione");
      if($errNome != "")
        $errori++;
      if($errDescr != "")
        $errori++;

      // se non ci sono errori
      if($errori == 0) {
        if($con->updateTeInfusi($id, $descImg, $tipo, $nome, $ingre, $descr, $prepa)) {
          if($imgpresent) {
            if($image->uploadImageTeInfusi($_FILES['immagine']['name'], $_FILES['immagine']['tmp_name'], $id))
              $messaggio = "";
            else
              $messaggio = '<p class="error-msg">Errore: immagine non salvata</p>';
          }
        }
        else
          $messaggio = '<p class="error-msg">Query non eseguita riprovare pi&ugrave; tardi</p>';
      } // end if se non ci sono errori
      else {
        $messaggio = '<p class="error-msg">Errore: ci sono '.$errori.' errori</p>';
        $valnome = $_POST['Nome'];
        $valingre = $_POST['Ingredienti'];
        $valdescr = $_POST['Descrizione'];
        $valprepa = $_POST['Preparazione'];
        $valdescImg = $_POST['desc_img'];
      }
    } // end if se è stato premuto il buttone submit
    $con->closeConnection();

    $pagina = str_replace("%action%", "updateTeInfusi.php?id=".$id, $pagina);
    $pagina = str_replace("%nome%", $valnome, $pagina);
    $pagina = str_replace("%ingre%", $valingre, $pagina);
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

  function validInput($name, $description) {
    return ($name!="" ? "" : '<small class="error-msg">Il campo '.$description.' &egrave; richiesto</small>');
  }
?>

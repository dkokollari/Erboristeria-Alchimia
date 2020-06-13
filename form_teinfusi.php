<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("Image.php");
  require_once("control_input.php");

  if($_SESSION['tipo_utente'] != 'Admin'){
    header('Location: redirect.php?error=3');
    exit;
  }

  $con = new DBAccess();
  if($con->openConnection()) {

    require_once('menu_pagina.php');

    $pagina = file_get_contents('base.html');
    $pagina = str_replace("%TITOLO_PAGINA%", "Form T&egrave; e Infusi", $pagina);
    $pagina = str_replace("%DESCRIZIONE_PAGINA%", 'pagina inserimento e modifica di un t&egrave; o Infuso', $pagina);
    $pagina = str_replace("%KEYWORDS_PAGINA%", 'inserimento, modifica, form, tè, infuso, erboristeria, alchimia', $pagina);
    $pagina = str_replace("%CONTAINER_PAGINA%", "container", $pagina);
    $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("form_teinfusi.php"), $pagina);
    $contenuto = file_get_contents('form_teinfusi.html');

    $messaggio = "";
    $valnome = "";
    $valingre = "";
    $valdescr = "";
    $valprepa = "";
    $messaggio = "";
    $errNome = "";
    $errImg= "";
    $errDescr="";
    $descImg="";
    $errori = 0;

    if(isset($_GET['id'])){  // se è una modifica
      $id = $_GET['id'];
      $getElement = $con->getSingoloTeInfuso($id);
      $valtipo = htmlentities($getElement[0]['tipo_te_e_infusi']);
      $valnome = htmlentities($getElement[0]['nome_te_e_infusi']);
      $valingre = htmlentities($getElement[0]['ingredienti_te_e_infusi']);
      $valdescr = htmlentities($getElement[0]['descrizione_te_e_infusi']);
      $valprepa = htmlentities($getElement[0]['preparazione_te_e_infusi']);
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
        $errImg = $image->isValid($_FILES['immagine']['name']);
        if($errImg!="") {
          $errori++;
          $pagina = str_replace("%ERR_IMG%", $errImg, $pagina);
        }
        $descImg = "immagine del " . $tipo ." " .$nome;
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
      }
    } //endif se è stato premuto il buttone submit

    $contenuto = str_replace("%nome%", $valnome, $contenuto);
    $contenuto = str_replace("%ingre%", htmlentities($valingre), $contenuto);
    $contenuto = str_replace("%descr%", $valdescr, $contenuto);
    $contenuto = str_replace("%prepa%", $valprepa, $contenuto);
    $contenuto = str_replace("%ERR_NOME%", $errNome, $contenuto);
    $contenuto = str_replace("%MESSAGGIO%", $messaggio, $contenuto);
    $contenuto = str_replace("%ERR_DESC%", $errDescr, $contenuto);
    $contenuto = str_replace("%ERR_IMG%", $errImg, $contenuto);

    $pagina = str_replace("%ICONA_CARRELLO%", '', $pagina);
    $pagina = str_replace("%CONTENUTO_PAGINA%", $contenuto, $pagina);

    echo $pagina;
  }
  else {
    header('Location: redirect.php?error=1');
    exit;
  }
?>

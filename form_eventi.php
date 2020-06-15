<?php
require_once("session.php");
require_once("DBAccess.php");
require_once("Image.php");
require_once("control_input.php");

/*if($_SESSION['tipo_utente'] != 'Admin'){
  header('Location: redirect.php?error=3');
  exit;
}*/

$con = new DBAccess();
if($con->openConnection()) {

  require_once('menu_pagina.php');

  $pagina = file_get_contents('base.html');
  $pagina = str_replace("%TITOLO_PAGINA%", "Nuovo evento", $pagina);
  $pagina = str_replace("%DESCRIZIONE_PAGINA%", 'pagina inserimento di un nuovo evento', $pagina);
  $pagina = str_replace("%KEYWORDS_PAGINA%", 'inserimento, evento, form, erboristeria, alchimia', $pagina);
  $pagina = str_replace("%CONTAINER_PAGINA%", "container", $pagina);
  $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("form_eventi.php"), $pagina);
  $contenuto = file_get_contents('form_eventi.html');

  $messaggio = "";
  $err_tit="";
  $oggi = (new DateTime())->format('Y-m-d') . 'T'. (new DateTime())->format('H:i');
  $val_titolo = "";
  $val_stt1 = "";
  $val_stt2 = "";
  $val_stt3 = "";
  $val_stt4 = "";
  $val_stt5 = "";
  $val_rel = "";
  $val_map = "";
  $descr_ind = "";
  $val_org = "";
  $descr_ind="";
  $err_rel="";
  $err_desc="";
  $err_ind="";
  $err_org="";
  $err_img="";
  $errori = 0;

    if(isset($_POST['submit'])) {

       $titolo =  control_input::name_control($_POST['titolo_evento']);

       if(!$titolo){
         $err_tit = 'il titolo deve contenere almeno 5 caratteri(non numeri e caratteri speciali)';
         $errori++;
       }

       if(empty($_POST['sottotitolo1']) && empty($_POST['sottotitolo2']) && empty($_POST['sottotitolo3'])
            && empty($_POST['sottotitolo4']) && empty($_POST['sottotitolo5'])) {
              $err_desc = 'inserisci almeno un sottotitolo';
              $errori++;
       }

      //controllo sottotioli
      $stt1 = control_input::desctitoli_control($_POST['sottotitolo1']);
      $stt2 = control_input::desctitoli_control($_POST['sottotitolo2']);
      $stt3 = control_input::desctitoli_control($_POST['sottotitolo3']);
      $stt4 = control_input::desctitoli_control($_POST['sottotitolo4']);
      $stt5 = control_input::desctitoli_control($_POST['sottotitolo5']);

       if((!empty($_POST['sottotitolo1']) && !$stt1) || (!empty($_POST['sottotitolo2']) && !$stt2) ||
        (!empty($_POST['sottotitolo3']) && !$stt3) || (!empty($_POST['sottotitolo4']) && !$stt4) ||
        (!empty($_POST['sottotitolo5']) && !$stt5)){
         $err_desc = 'i sottotitoli devono contenere almeno 5 caratteri(non pi&ugrave; di 100)';
         $errori++;
       }

       //controllo relatori
       $relatori =  control_input::rel_control($_POST['relatori']);

       if(!empty($_POST['relatori']) && !$relatori){
         $err_rel = 'relatori deve contenere almeno 5 caratteri(non numeri e caratteri speciali)';
         $errori++;
       }
       if(empty($_POST['relatori'])){
         $relatori = 'Non ci sono relatori per questo evento';
       }

       //controllo indirizzo
       if(empty($_POST['mappa_evento']) || empty($_POST['desc_mappa_evento'])){
         $err_ind = "inserire l'indirizzo e la descrizione dell'evento";
         $errori++;
       }
       else{
           $mappa = control_input::ind_control($_POST['mappa_evento']);
           $desc_map = control_input::description_control($_POST['desc_mappa_evento']);
           if(!$mappa){
             $err_ind = "controllare che sia corretto l'indirizzo";
             $errori++;
           }
           if(!$desc_map){
             $err_ind = "la descrizione dell'indirizzo deve contenere almeno 20 caratteri(non pi&ugrave; di 500)";
             $errori++;
           }
       }

       $org='Nessuna Organizzazione per questo evento';
       if(!empty($_POST['organizzazione_evento'])){
         $org = control_input::ind_control($_POST['organizzazione_evento']);
         if(!$org){
           $err_org = 'deve contenere almeno 5 caratteri(non caratteri speciali)';
           $errori++;
         }
       }

       $image = new Image();
       $imgpresent = false;
       $descImg = "Nessuna immagine per questo evento" ;
       // controllo se un'immagine è stata caricata
       if(is_uploaded_file($_FILES['immagine']['tmp_name'])) {
         $imgpresent = true;
         $errImg = $image->isValid($_FILES['immagine']['name']);
         if($errImg!="") {
           $errori++;
         }
         $descImg = "immagine dell'evento " .$titolo;
       }


       if($errori==0){// no erori

          $data = (new DateTime($_POST['dataora_evento']))->format('Y-m-d H:i:s');

          if($con->insertEventi($descImg, $titolo,$data,$relatori, $mappa,$org)){
            $id = $con->getId_Eventi($titolo);

            $con->insertMappaEventi($mappa, $desc_map);

            if(isset($_POST['sottotitolo1'])) $con->insertDescrizioneEventi($id, $stt1);
            if(isset($_POST['sottotitolo2'])) $con->insertDescrizioneEventi($id, $stt2);
            if(isset($_POST['sottotitolo3'])) $con->insertDescrizioneEventi($id, $stt3);
            if(isset($_POST['sottotitolo4'])) $con->insertDescrizioneEventi($id, $stt4);
            if(isset($_POST['sottotitolo5'])) $con->insertDescrizioneEventi($id, $stt5);

            header('Location: eventi.php');
            exit;

          }else {  //query non eseguita
            header('Location: redirect.php?error=4');
            exit;
          }

       }
       else{ //errori
         $messaggio = '<p class="error-msg">Errore: ci sono '.$errori.' errori</p>';
         $val_titolo = $_POST['titolo_evento'];
         $val_stt1 = $_POST['sottotitolo1'];
         $val_stt2 = $_POST['sottotitolo2'];
         $val_stt3 = $_POST['sottotitolo3'];
         $val_stt4 = $_POST['sottotitolo4'];
         $val_stt5 = $_POST['sottotitolo5'];
         $val_rel = $_POST['relatori'];
         $val_map = $_POST['mappa_evento'];
         $descr_ind = $_POST['desc_mappa_evento'];
         $val_org = $_POST['organizzazione_evento'];

       }

    }


  $contenuto = str_replace("%MESSAGGIO%", $messaggio, $contenuto);
  $contenuto = str_replace("%val_titolo%", $val_titolo, $contenuto);
  $contenuto = str_replace("%val_stt1%", $val_stt1, $contenuto);
  $contenuto = str_replace("%val_stt2%", $val_stt2, $contenuto);
  $contenuto = str_replace("%val_stt3%", $val_stt3, $contenuto);
  $contenuto = str_replace("%val_stt4%", $val_stt4, $contenuto);
  $contenuto = str_replace("%val_stt5%", $val_stt5, $contenuto);
  $contenuto = str_replace("%val_rel%", $val_rel, $contenuto);
  $contenuto = str_replace("%val_map%", $val_map, $contenuto);
  $contenuto = str_replace("%descr_ind%", $descr_ind, $contenuto);
  $contenuto = str_replace("%val_org%", $val_org, $contenuto);

  $contenuto = str_replace("%OGGI%", $oggi, $contenuto);
  $contenuto = str_replace("%ERR_TIT%", $err_tit, $contenuto);
  $contenuto = str_replace("%descr_ind%", $descr_ind, $contenuto);
  $contenuto = str_replace("%ERR_REL%", $err_rel, $contenuto);
  $contenuto = str_replace("%ERR_DESC%", $err_desc, $contenuto);
  $contenuto = str_replace("%ERR_IND%", $err_ind, $contenuto);
  $contenuto = str_replace("%ERR_ORG%", $err_org, $contenuto);
  $contenuto = str_replace("%ERR_IMG%", $err_img, $contenuto);

  $pagina = str_replace("%ICONA_CARRELLO%", '', $pagina);
  $pagina = str_replace("%CONTENUTO_PAGINA%", $contenuto, $pagina);

  echo $pagina;

}
else {
  header('Location: redirect.php?error=1');
  exit;
}
 ?>

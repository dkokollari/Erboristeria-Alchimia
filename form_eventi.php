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
  $val_rel1 = "";
  $val_rel2 = "";
  $val_rel3 = "";
  $val_rel4 = "";
  $val_rel5 = "";
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
       if(empty($_POST['relatore1']) && empty($_POST['relatore2']) && empty($_POST['relatore3'])
         && empty($_POST['relatore4']) && empty($_POST['relatore5'])){
           $err_rel = 'inserire almeno un relatore';
           $errori++;
       }
       $rel1 =  control_input::name_control($_POST['relatore1']);
       $rel2 =  control_input::name_control($_POST['relatore2']);
       $rel3 =  control_input::name_control($_POST['relatore3']);
       $rel4 =  control_input::name_control($_POST['relatore4']);
       $rel5 =  control_input::name_control($_POST['relatore5']);

       if((!empty($_POST['relatore1']) && !$rel1) || (!empty($_POST['relatore2']) && !$rel2) ||
         (!empty($_POST['relatore3']) && !$rel3) || (!empty($_POST['relatore4']) && !$rel4) ||
         (!empty($_POST['relatore5']) && !$rel5) ){
         $err_rel = 'nomi di relatori non possono contenere numeri e caratteri speciali';
         $errori++;
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

       $org='';
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
          $relatori = '';
          if($rel1!= false) $relatori .= $rel1 .'<br/>';
          if($rel2!= false) $relatori .= $rel2 .'<br/>';
          if($rel3!= false) $relatori .= $rel3 .'<br/>';
          if($rel4!= false) $relatori .= $rel4 .'<br/>';
          if($rel5!= false) $relatori .= $rel5 .'<br/>';

          $data = (new DateTime($_POST['dataora_evento']))->format('Y-m-d H:i:s');

          if($con->insertEvento($descImg, $titolo,$data,$relatori, $mappa,$org)){
            $id = $con->getId_Evento($titolo);

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
         $val_rel1 = $_POST['relatore1'];
         $val_rel2 = $_POST['relatore2'];
         $val_rel3 = $_POST['relatore3'];
         $val_rel4 = $_POST['relatore4'];
         $val_rel5 = $_POST['relatore5'];
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
  $contenuto = str_replace("%val_rel1%", $val_rel1, $contenuto);
  $contenuto = str_replace("%val_rel2%", $val_rel2, $contenuto);
  $contenuto = str_replace("%val_rel3%", $val_rel3, $contenuto);
  $contenuto = str_replace("%val_rel4%", $val_rel4, $contenuto);
  $contenuto = str_replace("%val_rel5%", $val_rel5, $contenuto);
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

<?php
  require_once("session.php");
  require_once("genera_pagina.php");
  require_once("DBAccess.php");

  if($_SESSION['auth']) { // utente con sessione
    header('location: index.php');
    exit;
  }
  else if(isset($_COOKIE['email']) && $_COOKIE['email']!="") { // utente con cookie senza sessione
    $aux = new DBAccess();
    if(!$aux->openConnection()) {
      header('Location: redirect.php?error=1');
      exit;
    }
    else {
      $temp = $aux->getSingolo_Utenti($_COOKIE['email']);
      if(empty($temp) ||
          $_COOKIE['password'] != $temp[0]['password_utente']) { // utente non trovato o password diversa
        setcookie("email", "", time()-3600);
        setcookie("password", "", time()-3600);
      }
      else {
        setSessione($temp);
        header('location: index.php');
        exit;
      }
    }
  } // end else if utente con cookie senza sessione

  // utente senza cookie e senza sessione
  if($_POST['Login']) {
    $email = mysql_real_escape_string(trim($_POST['email']));
    $password = mysql_real_escape_string(trim($_POST['password']));
    $minLengthPwd = 8;
    $maxLengthPwd = 100;
    $errore_empty = '<span class="errore">Inserire sia una email che una password</span>';
    $errore_wrong = '<span class="errore">La email o la password inserite non sono corrette</span>';
    if(empty($email) || empty($password)) $errore = $errore_empty;
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL) ||
               (strlen($password) < $minLengthPwd || strlen($password) > $maxLengthPwd)) {
      $errore = $errore_wrong;
    }
    else { // email e password valide
      $con = new DBAccess();
      if(!$con->openConnection()) {
        header('Location: redirect.php?error=1');
        exit;
      }
      else {
        $utente = $con->getSingolo_Utenti($email);
        if(empty($utente)) $errore = $errore_wrong;
        else {
          $passwordCheck = password_verify($password, $utente[0]['password_utente']);
          if(!$passwordCheck) $errore = $errore_wrong;
          else {
            setSessione($utente);
            if(isset($_POST['remember_me'])) {
              setcookie('email', $email, time()+60*60*24*30);
              setcookie('password', $utente[0]['password_utente'], time()+60*60*24*30);
            }
            header('location: index.php');
          }
        }
        $con->closeConnection();
      }
    } // end else if email e password valide
  } // end if $_POST["Login"]

  $contenuto = file_get_contents("../html/login.html");
  $contenuto = str_replace("%ERR_LOGIN%", $errore, $contenuto);
  $contenuto = str_replace("%LOGIN_STATUS%", $logged, $contenuto);
  $pagina = Genera_pagina::genera("../html/base.html", "login", $contenuto);
  echo $pagina;

  function setSessione($array="") { // guardare DBAccess::getSingolo_Utenti() per vedere la struttura di $array
    $_SESSION['auth'] = true;
    $_SESSION['nome_utente'] = $array[0]['nome_utente'];
    $_SESSION['cognome_utente'] = $array[0]['cognome_utente'];
    $_SESSION['email_utente'] = $array[0]['email_utente'];
    $_SESSION['password_utente'] = $array[0]['password_utente'];
    $_SESSION['tipo_utente'] = $array[0]['tipo_utente'];
    $_SESSION['data_nascita_utente'] = $array[0]['data_nascita_utente'];
  }
?>

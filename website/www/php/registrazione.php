<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("genera_pagina.php");
  require_once("validate_form.php");

  if($_POST['Registrati']) {
    $nome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['nome']))));
    $cognome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['cognome']))));
    $email = mysql_real_escape_string(trim($_POST['email']));
    $password = mysql_real_escape_string(trim($_POST['password']));
    $password_conferma = mysql_real_escape_string(trim($_POST['password_conferma']));
    $data_nascita = mysql_real_escape_string(trim($_POST['data_nascita']));
    // messaggi di errore
    $errore_empty = '<span class="errore">Completa tutti i campi</span>';
    $errore_full = '<span class="errore">Questa e-mail sembra non essere disponibile</span>';
    $errore_nome = '<span class="errore">Inserisci un nome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
    $errore_cognome = '<span class="errore">Inserisci un cognome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
    $errore_email = '<span class="errore">Inserisci una email valida</span>';
    $errore_password = '<span class="errore">Inserisci una password di lunghezza tra 8 e 100 caratteri, almeno 1 lettera ed 1 numero</span>'; // riferirsi alle regole di validate_form
    $errore_conferma = '<span class="errore">Le password inserite non corrispondono</span>';

    $fields = [$email, $password, $data_nascita];
    if(Validate_form::is_empty($fields))
      $errore = $errore_empty;
    else if(!empty($nome) && !Validate_form::check_str($nome))
      $errore = $errore_nome;
    else if(!empty($cognome) && !Validate_form::check_str($cognome))
      $errore = $errore_cognome;
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
      $errore = $errore_email;
    else if(!Validate_form::check_pwd($password))
      $errore = $errore_password;
    else if($password != $password_conferma)
      $errore = $errore_conferma;
    else {
      $con = new DBAccess();
      if(!$con->openConnection()){
        header('Location: redirect.php?error=1');
        exit;
      }

      if($con->getSingolo_Utenti($email))
        $errore = $errore_full;
      else
        $con->insertUtenti($nome,
                           $cognome,
                           $email,
                           $password,
                           $data_nascita);
      $con->closeConnection();
    }

    if(empty($errore)) {
      // suicide solution - https://stackoverflow.com/a/32502860
      header('HTTP/1.1 307 Temporary Redirect');
      header('Location: login.php');
      exit;
    }
    else {
      $status = $errore;
    }
  } // end if $_POST['Registrati']

  $contenuto = file_get_contents("../html/form_utente.html");
  $contenuto = str_replace("%ACTION_FORM%", "registrazione.php", $contenuto);
  $contenuto = str_replace("%TITOLO%", "Registrazione a portata di un click", $contenuto);
  $contenuto = str_replace("%STATO_UTENTE%", $status, $contenuto);
  $contenuto = str_replace("%VALUE_nome%", "", $contenuto);
  $contenuto = str_replace("%VALUE_cognome%", "", $contenuto);
  $contenuto = str_replace("%VALUE_email%", "", $contenuto);
  $contenuto = str_replace("%VALUE_data_nascita%", "", $contenuto);
  $contenuto = str_replace("%NOME_SUBMIT%", "Registrati", $contenuto);
  $contenuto = str_replace("%AGGIUNGI_SUBMIT%", '<span>Sei gi&agrave; registrato? <a href="login.php">CLICCA QUI</a></span>', $contenuto);
  $pagina = Genera_pagina::genera("../html/base5.html", "registrazione", $contenuto);
  echo $pagina;
?>

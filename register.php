<?php
    require_once("DBAccess.php");
    require_once("validate_form.php");

    $pagina = file_get_contents('register.html');
    if($_POST['Registrati']){
      $nome = mysql_real_escape_string(trim($_POST['nome']));
      $cognome = mysql_real_escape_string(trim($_POST['cognome']));
      $email = mysql_real_escape_string(trim($_POST['email']));
      $password = mysql_real_escape_string(trim($_POST['password']));
      $data_nascita = mysql_real_escape_string(trim($_POST['data_nascita']));
      /* messaggi di errore */
      $errore_empty = '<p class="errore">Completa tutti i campi</p>';
      $errore_full = '<p class="errore">Questo utente sembra essere gi&agrave; registrato. Hai dimenticato la password?</p>';
      $errore_nome = '<p class="errore">Inserisci un nome di lunghezza tra 3 e 200 caratteri</p>'; // riferirsi alle regole di validate_form
      $errore_cognome = '<p class="errore">Inserisci un cognome di lunghezza tra 3 e 200 caratteri</p>'; // riferirsi alle regole di validate_form
      $errore_email = '<p class="errore">Inserisci una email valida</p>';
      $errore_password = '<p class="errore">Inserisci una password di lunghezza tra 8 e 12 caratteri, almeno 1 lettera ed 1 numero</p>'; // riferirsi alle regole di validate_form.php

      if(Validate_form::is_empty([$nome, $cognome, $email, $password, $data_nascita])){
        $errore = $errore_empty;
      }
      else if(!Validate_form::check_str($nome)){
        $errore = $errore_nome;
      }
      else if(!Validate_form::check_str($cognome)){
        $errore = $errore_cognome;
      }
      else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errore = $errore_email;
      }
      else if(!Validate_form::check_pwd($password)){
        $errore = $errore_password;
      }
      else{
        $con = new DBAccess();
        if(!$con->openConnection()){
         echo '<p class="errore">Impossibile connettersi al database riprovare pi&ugrave; tardi</p>';
         exit;
        }

        if($con->getUser($email)){
          $errore = $errore_full;
        }
        else{
          $con->insertUser($nome,
                           $cognome,
                           $email,
                           $password,
                           $data_nascita);
        }

        $con->closeConnection();
      }
    }

    $status = (($errore=="")?
                "<p>Registrazione riuscita</p>"
              : "<p>Registrazione fallita</p>");
    $pagina = str_replace("%REGISTER_STATUS%", $status, $pagina);
    $pagina = str_replace("%REGISTER_ERROR%", $errore, $pagina);
    echo $pagina;
?>

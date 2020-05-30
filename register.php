<?php
    require_once("DBAccess.php");

    $pagina = file_get_contents('register.html');
    if($_POST['Registrati']){
      $nome = mysql_real_escape_string(trim($_POST['nome']));
      $cognome = mysql_real_escape_string(trim($_POST['cognome']));
      $email = mysql_real_escape_string(trim($_POST['email']));
      $password = mysql_real_escape_string(trim($_POST['password']));
      $data_nascita = mysql_real_escape_string(trim($_POST['data_nascita']));
      $minLengthPwd = 8;
      $maxLengthPwd = 12;
      $errore_empty = '<p class="errore">Completa tutti i campi</p>';
      $errore_full = '<p class="errore">Questo utente sembra essere gi&agrave; registrato</p>';
      $errore_email = '<p class="errore">Inserisci una email valida</p>';
      $errore_password = '<p class="errore">Inserisci una password tra gli 8 e i 12 caratteri</p>';

      if(empty($nome)
      || empty($cognome)
      || empty($email)
      || empty($password)
      || empty($data_nascita)){
        $errore = $errore_empty;
      }
      else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errore = $errore_email;
      }
      else if(strlen($password) < $minLengthPwd || strlen($password) > $maxLengthPwd){
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
        else{}

        $con->closeConnection();
      }
    }
?>

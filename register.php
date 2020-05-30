<?php
    require_once("DBAccess.php");

    $pagina = file_get_contents('register.html');
    if($_POST['Registrati']){
      $nome = mysql_real_escape_string(trim($_POST['nome']));
      $cognome = mysql_real_escape_string(trim($_POST['cognome']));
      $email = mysql_real_escape_string(trim($_POST['email']));
      $password = mysql_real_escape_string(trim($_POST['password']));
      $data_nascita = mysql_real_escape_string(trim($_POST['data_nascita']));
      /* requisiti campi */
      $minLengthUsr = 2;
      $maxLengthUsr = 20;
      $minLengthPwd = 8;
      $maxLengthPwd = 12;
      /* messaggi di errore */
      $errore_empty = '<p class="errore">Completa tutti i campi</p>';
      $errore_full = '<p class="errore">Questo utente sembra essere gi&agrave; registrato</p>';
      $errore_nome = '<p class="errore">Inserisci un nome tra '.$minLengthUsr.' e '.$maxLengthUsr.' caratteri</p>';
      $errore_cognome = '<p class="errore">Inserisci un cognome tra '.$minLengthUsr.' e '.$maxLengthUsr.' caratteri</p>';
      $errore_email = '<p class="errore">Inserisci una email valida</p>';
      $errore_password = '<p class="errore">Inserisci una password tra gli '.$minLengthPwd.' e i '.$maxLengthPwd.' caratteri</p>';

      if(empty($nome)
      || empty($cognome)
      || empty($email)
      || empty($password)
      || empty($data_nascita)){
        $errore = $errore_empty;
      }
      else if(strlen($nome) < $minLengthUsr || strlen($nome) > $maxLengthUsr){
        $errore = $errore_nome;
      }
      else if(strlen($cognome) < $minLengthUsr || strlen($cognome) > $maxLengthUsr){
        $errore = $errore_cognome;
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

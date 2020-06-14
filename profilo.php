<?php
  // require_once("session.php");
  require_once("DBAccess.php");
  require_once("validate_form.php");

  session_start();
  $_SESSION['auth'] = true;
  $_SESSION['nome_utente'] = "Mario";
  $_SESSION['cognome_utente'] = "Rossi";
  $_SESSION['email_utente'] = "test@test.it";
  $_SESSION['data_nascita_utente'] = "2020-06-10";

  if($_SESSION['auth']) {
    $pagina = file_get_contents('register.html');
    // sostituzione elementi di registrazione
    $pagina = str_replace('<title>Registrati - Erboristeria Alchimia</title>', '<title>Modifica profilo - Erboristeria Alchimia</title>', $pagina);
    $pagina = str_replace('<meta name="title" content="Registrati ad Erboristeria Alchimia"/>', '<meta name="title" content="Modifica il profilo di Erboristeria Alchimia"/>', $pagina);
    $pagina = str_replace('<meta name="description" content="Pagina di registrazione al sito"/>', '<meta name="description" content="Pagina di modifica del profilo"/>', $pagina);
    $pagina = str_replace('<meta name="keywords" content="registrazione, email, password, erboristeria, alchimia"/>', '<meta name="keywords" content="modifica, aggiorna, profilo, profili, nome, cognome, username, e-mail, mail, password, data, erboristeria, alchimia"/>', $pagina);
    $pagina = str_replace('%REGISTER_STATUS%', '%STATUS_PROFILE%', $pagina);
    $pagina = str_replace('<form action="register.php" method="post">', '<form action="profilo.php" method="post">', $pagina);
    $pagina = str_replace('<h2>Registrati</h2>', '<h2>Modifica profilo</h2>', $pagina);
    $pagina = str_replace('%REGISTER_ERROR%', '%ERROR_PROFILE%', $pagina);
    $pagina = str_replace('<input id="log_in" type="submit" name="Registrati"/>', '<input id="log_in" type="submit" name="Modifica_profilo"/>', $pagina);
    // pre-fill campi input
    $array_place_html = ['nome', 'cognome', 'username', 'data_nascita'];
    $array_place_session = ['nome_utente', 'cognome_utente', 'email_utente', 'data_nascita_utente'];
    foreach (array_combine($array_place_html, $array_place_session) as $html => $session) {
      if($_SESSION[$session]!="") {
        $pagina = str_replace('<label for="'.$html.'">', '<label class="filled" for="'.$html.'"', $pagina);
        $pagina = str_replace('%VALUE_'.$html.'%', 'value="'.$_SESSION[$session].'"', $pagina);
      }
    }

    if($_POST['Modifica_profilo']) {
      $nome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['nome']))));
      $cognome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['cognome']))));
      $email = mysql_real_escape_string(trim($_POST['username']));
      $password = mysql_real_escape_string(trim($_POST['password']));
      $password_conferma = mysql_real_escape_string(trim($_POST['password_conferma']));
      $data_nascita = mysql_real_escape_string(trim($_POST['data_nascita']));
      /* messaggi di errore */
      $errore_empty = '<span class="errore">Completa tutti i campi</span>';
      $errore_full = '<span class="errore">Questa e-mail sembra non essere disponibile</span>';
      $errore_nome = '<span class="errore">Inserisci un nome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
      $errore_cognome = '<span class="errore">Inserisci un cognome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
      $errore_email = '<span class="errore">Inserisci una email valida</span>';
      $errore_password = '<span class="errore">Inserisci una password di lunghezza tra 8 e 100 caratteri, almeno 1 lettera ed 1 numero</span>'; // riferirsi alle regole di validate_form
      $errore_conferma = '<span class="errore">Le password inserite non corrispondono</span>';
      $errore_sconosciuto = '<span class="errore">Per favore disconnettiti e accedi di nuovo</span>';

      $fields = [$nome, $cognome, $email, $password, $data_nascita];
      if(Validate_form::is_empty($fields)) {
        $errore = $errore_empty;
      }
      else if(!Validate_form::check_str($nome)) {
        $errore = $errore_nome;
      }
      else if(!Validate_form::check_str($cognome)) {
        $errore = $errore_cognome;
      }
      else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errore = $errore_email;
      }
      else if(!Validate_form::check_pwd($password)) {
        $errore = $errore_password;
      }
      else if($password != $password_conferma) {
        $errore = $errore_conferma;
      }
      else {
        $con = new DBAccess();
        if(!$con->openConnection()) {
         echo '<span class="errore">Impossibile connettersi al database riprovare pi&ugrave; tardi</span>';
         exit;
        }
        if($email != $_SESSION['email_utente'] && $con->getUser($email)) {
          $errore = $errore_full;
        }
        else {
          $result = $con->updateUser($_SESSION['email_utente'], $nome, $cognome, $email, $password, $data_nascita);
          if(!$result) {
            $errore = $errore_sconosciuto;
          }
          else {
            $array = $con->getUser($email);
            $_SESSION['nome_utente'] = $array[0]['nome_utente'];
            $_SESSION['cognome_utente'] = $array[0]['cognome_utente'];
            $_SESSION['email_utente'] = $array[0]['email_utente'];
            $_SESSION['password_utente'] = $array[0]['password_utente'];
            $_SESSION['tipo_utente'] = $array[0]['tipo_utente'];
            $_SESSION['data_nascita_utente'] = $array[0]['data_nascita_utente'];
          }
        }
        $con->closeConnection();
      }

      $status = (empty($errore)
                ? "<span>Profilo aggiornato con successo</span>"
                : "<span>Aggiornamento fallito</span>");
    } // end if $_POST['Modifica_profilo']
    $pagina = str_replace('%STATUS_PROFILE%', $status, $pagina);
    $pagina = str_replace('%ERROR_PROFILE%', $errore, $pagina);
    echo $pagina;
  } // end if $_SESSION['auth']
  else {
    echo "non devi stare qui";
  }
?>

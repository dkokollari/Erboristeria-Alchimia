<?php
  // require_once("session.php");
  session_start();
  $_SESSION['auth'] = true;
  $_SESSION['nome_utente'] = "Mario";
  $_SESSION['cognome_utente'] = "Rossi";
  $_SESSION['email_utente'] = "mario.rossi@gmail.com";
  $_SESSION['data_nascita_utente'] = "";

  if($_SESSION['auth']) {
    // sostituzione elementi di registrazione
    $pagina = file_get_contents('register.html');
    $pagina = str_replace('%REGISTER_STATUS%', '%STATUS_PROFILE%', $pagina);
    $pagina = str_replace('<form action="register.php" method="post">',
                          '<form action="profilo.php" method="post">', $pagina);
    $pagina = str_replace('<h2>Registrati</h2>',
                          '<h2>Modifica profilo</h2>', $pagina);
    $pagina = str_replace('%REGISTER_ERROR%', '%ERROR_PROFILE%', $pagina);
    $pagina = str_replace('<input id="log_in" type="submit" name="Registrati"/>',
                          '<input id="log_in" type="submit" name="Modifica_profilo"/>', $pagina);
    // pre-fill campi input
    if($_SESSION['nome_utente']!="") {
      $pagina = str_replace('<label for="nome">',
                            '<label class="filled" for="nome">', $pagina);
      $pagina = str_replace('<input id="nome" name="nome" type="text"/>',
                            '<input id="nome" name="nome" type="text" value="'.$_SESSION['nome_utente'].'"/>', $pagina);
    }
    if($_SESSION['cognome_utente']!="") {
      $pagina = str_replace('<label for="cognome">',
                            '<label class="filled" for="cognome">', $pagina);
      $pagina = str_replace('<input id="nome" name="cognome" type="text"/>',
                            '<input id="nome" name="cognome" type="text" value="'.$_SESSION['cognome_utente'].'"/>', $pagina);
    }

    if($_POST['Modifica_profilo']) {
    }

    $pagina = str_replace('%STATUS_PROFILE%', '', $pagina);
    $pagina = str_replace('%ERROR_PROFILE%', '', $pagina);
    echo $pagina;
  }
  else {
    echo "non devi stare qui";
  }
?>

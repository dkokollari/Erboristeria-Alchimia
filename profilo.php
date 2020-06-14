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

    $array_place_html = ['nome', 'cognome', 'username'];
    $array_place_session = ['nome_utente', 'cognome_utente', 'email_utente'];
    // pre-fill campi input
    foreach (array_combine($array_place_html, $array_place_session) as $html => $session) {
      if($_SESSION[$session]!="") {
        $pagina = str_replace('<label for="'.$html.'">',
                              '<label class="filled" for="'.$html.'">', $pagina);
        $pagina = str_replace('<input id="'.$html.'" name="'.$html.'" type="text"/>',
                              '<input id="'.$html.'" name="'.$html.'" type="text" value="'.$_SESSION[$session].'"/>', $pagina);
      }
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

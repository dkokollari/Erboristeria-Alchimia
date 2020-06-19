<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("validate_form.php");

  if($_SESSION['auth']) {
    $pagina = file_get_contents("../html/register.html");
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

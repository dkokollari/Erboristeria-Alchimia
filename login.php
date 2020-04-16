<?php
require_once("DBAccess.php");

if(isset($POST['login'])) {
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit;
  }
  /*Stabilita connessione al db*/
  $pagina = file_get_contents('login.html');
  $errore = '<h1 class="errori"> La'.'email'. 'o la' .'password'. 'inserite sono sbagliate</h1>';
  $email = $POST['username']; // the username is one's email for us!
  $password = $POST['password'];

  if(!$conn->query("SELECT * FROM utenti
     WHERE email_utente = '".$email."' and password_utente = '".$password."'") {
    $pagina = str_replace("%ERR_LOGIN%", $errore, $pagina);
  }

  session_start(); //TODO fare pagine es i miei te e infusi riempita coi dati dell'utente presi dal db!
}
?>

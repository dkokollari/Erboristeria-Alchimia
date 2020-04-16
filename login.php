<?php
require_once("DBAccess.php");

if(isset($POST['login'])) {
  $conn = new DBAccess(); //TODO FA SCHIFO RIPETERE STE RIGHE IN OGNI PAGINA PHP: MODULARIZZARE!
  if(!$conn->openConnection()) {
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit();
  }

  /*Stabilita connessione al db*/
  $minLengthPwd = 8;
  $maxLengthPwd = 12;
  $pagina = file_get_contents('login.html');
  $errore = "";
  $logged = "";
  $email = $POST['username']; // the username is one's email for us!
  $password = $POST['password'];

  if(!isset($email) || !isset($password)) {
    $errore = '<h1 class= "errori"><h1>'. 'Inserire sia una' .'email'. 'che una'.'password';
  }

  else if(!filter_input(INPUT_POST, $email, FILTER_VALIDATE_EMAIL)
    || (strlen($password) < $minLengthPwd || strlen($password) > $maxLengthPwd)) {
    $errore = '<h1 class="errori"> La'.'email'. 'o la' .'password'. 'inserite non sono corrette</h1>';
  }

  else {
    /*password e email inserite dall'utente: ora controllo che ci siano nel db*/
    $query = "SELECT * FROM utenti WHERE email_utente = ?";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $query)) {
      echo "Errore della query:" . mysqli_error($connessione) . ".";
      exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($result->mysqli_num_rows($result) == 0) {
      $errore = '<h1 class="errori"> La'.'email'. 'o la' .'password'. 'inserite non sono corrette</h1>';
    }
    else {
      $row = mysqli_fetch_assoc($result);
      $passwordCheck = password_verify($password, $row['password_utente']);
      if($passwordCheck == false) {
        $errore = '<h1 class="errori"> La'.'email'. 'o la' .'password'. 'inserite non sono corrette</h1>';
      }
      else {
        session_start(); //TODO decidere cosa possono vedere gli utenti registrati!
        $logged = "SEI LOGGATO, GUAGLIONE!!!";
        $_SESSION['email_utente'] =  $row['email_utente'];
      }
      mysqli_free_result($result);
    }
  }

  $pagina = str_replace("%ERR_LOGIN%", $errore , $pagina);
  $pagina = str_replace("%ERR_LOGIN%", $logged , $pagina);
  echo $pagina;
  $conn->closeConnection();

}
?>

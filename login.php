<?php
require_once("DBAccess.php");


$pagina = file_get_contents('login.html');
if(isset($_POST['Login'])) {

  $minLengthPwd = 8;
  $maxLengthPwd = 12;
  $errore = "";
  $logged = "";
  $email = $_POST['username']; // the username is one's email for us!
  $password = $_POST['password'];

  if(!isset($email) || !isset($password)) {
    $errore = '<div class= "errori">'. 'Inserire sia una ' .'email'. ' che una '.'password'.'</div>';
  }

  else if(!filter_var($email, FILTER_VALIDATE_EMAIL)
    || (strlen($password) < $minLengthPwd || strlen($password) > $maxLengthPwd)) {
    $errore = '<div class="errori"> La '.'email'. ' o la ' .'password'. ' inserite non sono corrette</div>';
  }

  else {
    /*password e email inserite dall'utente: ora controllo che ci siano nel db*/
    $query = "SELECT * FROM `utenti` WHERE `email_utente`=?";
    $conn = new DBAccess(); 
    if(!$conn->openConnection()) {
     echo '<div class= "errori">' . "Impossibile connettersi al database riprovare pi&ugrave; tardi" . '</div>';
     exit(1);
    }
    //Stabilita connessione al db
    
    if(!$stmt = mysqli_prepare($conn->connection, $query)) {
      die('prepare() failed: ' . htmlspecialchars(mysqli_error($conn->$connection)));
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows === 0) {
    $errore = '<div class="errori"> La '.'email'. ' o la ' .'password'. ' inserite non sono corrette</div>';
    }
    else {
      $row = $result->fetch_assoc();
      //$passwordCheck = password_verify($password, $row['password_utente']);
      /*inserimento solo tramite php cosi settiamo bcrypt come algoritmo!*/
      $passwordCheck = ($password == $row['password_utente']);
      $password = '<p>' . $password . '</p>';
      $prova = '<p>' . $row['password_utente'] . '</p>';
      if($passwordCheck == false) {
      $errore = '<div class="errori"> La '.'email'. ' o la ' .'password'. ' inserite non sono corrette</div>';
      }
      else {
        session_start(); //TODO decidere cosa possono vedere gli utenti registrati!
        $logged = '<div class="ok">' . 'SEI LOGGATO, GUAGLIONE!!! </div>';
        $_SESSION['email_utente'] =  $row['email_utente'];
      }
      $stmt->close();
    }
  }

  $pagina = str_replace("%ERR_LOGIN%", $errore , $pagina);
  $pagina = str_replace("%LOGIN_STATUS%", $logged , $pagina);
  echo $pagina;
  $conn->closeConnection();

}

/*utente non ha premuto il tasto submit*/
else { 
  $pagina = str_replace("%ERR_LOGIN%", "" , $pagina);
  $pagina = str_replace("%LOGIN_STATUS%", "" , $pagina);
  echo $pagina;
}

?>

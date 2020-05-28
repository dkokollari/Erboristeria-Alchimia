<?php
require_once("DBAccess.php");
require_once("sessione.php");

if($_SESSION['logged']==true){
    header('location:index.php');
    exit();
}


$pagina = file_get_contents('login.html');
$email = '';
$password = '';
$check = '';
/*if(isset($_COOKIE['email']) && isset($_COOKIE['password'])) {
  $email = $_COOKIE['user_email'];
  $password = $_COOKIE['password'];
  $check = 'checked="checked"';
} else*/
if($_POST['Login']) {
  if (!mysql_set_charset('utf8', $conn)) {
    echo "<p class=\"errore\">Error: Unable to set the character set!</p>\n";
    exit(1);
  }

  $email = mysql_real_escape_string(trim($_POST['email']));
  $password = mysql_real_escape_string(trim($_POST['password']));
  if(isset($_POST['remember_me'])) {
    $check = 'checked="checked"';
  }
  $minLengthPwd = 8;
  $maxLengthPwd = 12;
  $errore = "";
  $logged = "";
  if(!empty($email) || !empty($password)) {
    $errore = '<p class= "errore">'. 'Inserire sia una ' .'email'. ' che una '.'password'.'</p>';
  }

  else if(!filter_var($email, FILTER_VALIDATE_EMAIL)
    || (strlen($password) < $minLengthPwd || strlen($password) > $maxLengthPwd)) {
    $errore = '<p class="errore"> La '.'email'. ' o la ' .'password'. ' inserite non sono corrette</p>';
  }
  else {
    /*password e email inserite dall'utente: ora controllo che ci siano nel db*/
    $query = "SELECT * FROM `utenti` WHERE `email_utente`=?";
    $conn = new DBAccess();
    if(!$conn->openConnection()) {
     echo '<p class= "errore">' . "Impossibile connettersi al database riprovare pi&ugrave; tardi" . '</p>';
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
    $errore = '<p class="errore"> La '.'email'. ' o la ' .'password'. ' inserite non sono corrette</p>';
    }
    else {
      $row = $result->fetch_assoc();
      $passwordCheck = password_verify($password, $row['password_utente']);
      /*inserimento solo tramite php cosi settiamo bcrypt come algoritmo!*/
      if($passwordCheck == false) {
      $errore = '<p class="errore"> La '.'email'. ' o la ' .'password'. ' inserite non sono corrette</p>';
      }
      else {
        if(isset($_POST['remember_me'])){
          setcookie("email",$email,time()+60*60*24*30);
          setcookie("password",$password,time()+60*60*24*30);
        }
        $_SESSION['email_utente'] =  $row['email_utente'];
        $_SESSION['tipo_utente'] =  $row['tipo_utente'];
        header("location:index.php");
      }
      $stmt->close();
    }
  }

  $pagina = str_replace("%ERR_LOGIN%", $errore , $pagina);
  $pagina = str_replace("%LOGIN_STATUS%", $logged , $pagina);
  echo $pagina;
  $conn->closeConnection();

}

/*utente non ha premuto il tasto submit oppure l'ha premuto, */
else {
  $pagina = str_replace("%ERR_LOGIN%", "" , $pagina);
  $pagina = str_replace("%LOGIN_STATUS%", "" , $pagina);
  echo $pagina;
}

?>

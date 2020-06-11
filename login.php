<?php
  require_once("DBAccess.php");
  //require_once("sessione.php");
  session_start();

  if((isset($_SESSION['email_utente']) && $_SESSION['email_utente']="") ||
      (isset($_COOKIE['email']) && $_COOKIE['email']!="" && isset($_COOKIE['password']) && $_COOKIE['password']!="")) {
      header('location:index.php');
      exit;
  }

  $pagina = file_get_contents('login.html');
  $email = '';
  $password = '';
  if($_POST['Login']) {
    $email = mysql_real_escape_string(trim($_POST['email']));
    $password = mysql_real_escape_string(trim($_POST['password']));
    $minLengthPwd = 8;
    $maxLengthPwd = 12;
    $errore = "";
    $logged = "";
    $errore_empty = '<span class="errore">Inserire sia una email che una password</span>';
    $errore_wrong = '<span class="errore">La email o la password inserite non sono corrette</span>';
    if(empty($email) || empty($password)) $errore = $errore_empty;
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL) ||
               (strlen($password) < $minLengthPwd || strlen($password) > $maxLengthPwd)) {
      $errore = $errore_wrong;
    }
    else {
      /*password e email inserite dall'utente: ora controllo che ci siano nel db*/
      // WARNING: possibile codice duplicato e non aggiornato
      // TODO: usare DBAccess
      $con = new DBAccess();
      if(!$con->openConnection()) {
       echo '<span class="errore">Impossibile connettersi al database riprovare pi&ugrave; tardi</span>';
       exit;
      }
      //Stabilita connessione al db
      $query = "SELECT * FROM `utenti` WHERE `email_utente`=?";

      if(!$stmt = mysqli_prepare($con->connection, $query)) {
        die('prepare() failed: '.htmlspecialchars(mysqli_error($con->$conection)));
      }

      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if($result->num_rows === 0) $errore = $errore_wrong;
      else {
        $row = $result->fetch_assoc();
        $passwordCheck = password_verify($password, $row['password_utente']);
        if($passwordCheck == false) $errore = $errore_wrong;
        else {
          if(isset($_POST['remember_me'])) {
            setcookie("email",$email,time()+60*60*24*30);
            setcookie("password",$password,time()+60*60*24*30);
          }
          $_SESSION['email_utente'] = $row['email_utente'];
          $_SESSION['tipo_utente'] = $row['tipo_utente'];
          header("location:index.php");
        }
        $stmt->close();
      }
      $con->closeConnection();
    }

    $pagina = str_replace("%ERR_LOGIN%", $errore, $pagina);
    $pagina = str_replace("%LOGIN_STATUS%", $logged, $pagina);
    echo $pagina;
  } // end if $_POST['Login']
  else {
    $pagina = str_replace("%ERR_LOGIN%", "", $pagina);
    $pagina = str_replace("%LOGIN_STATUS%", "", $pagina);
    echo $pagina;
  }
?>

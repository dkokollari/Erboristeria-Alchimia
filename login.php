<?php
  require_once("DBAccess.php");
  //require_once("sessione.php");
  session_start();

  if($_SESSION['email_utente']
  || (isset($_COOKIE['email']) && isset($_COOKIE['password']))){
      header('location:index.php');
      exit;
  }

  $pagina = file_get_contents('login.html');
  $email = '';
  $password = '';
  if($_POST['Login']){
    $email = mysql_real_escape_string(trim($_POST['email']));
    $password = mysql_real_escape_string(trim($_POST['password']));
    const $minLengthPwd = 8;
    const $maxLengthPwd = 12;
    $errore = "";
    $logged = "";
    const $errore_empty = '<p class="errore">Inserire sia una email che una password</p>';
    const $errore_wrong = '<p class="errore">La email o la password inserite non sono corrette</p>';
    if(empty($email) || empty($password)){
      $errore = $errore_empty;
    }

    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)
    || (strlen($password) < $minLengthPwd || strlen($password) > $maxLengthPwd)){
      $errore = $errore_wrong;
    }
    else{
      /*password e email inserite dall'utente: ora controllo che ci siano nel db*/
      $conn = new DBAccess();
      if(!$conn->openConnection()){
       echo '<p class="errore">Impossibile connettersi al database riprovare pi&ugrave; tardi</p>';
       exit;
      }
      //Stabilita connessione al db
      $query = "SELECT * FROM `utenti` WHERE `email_utente`=?";
  	  if(!$conn->connection->set_charset("utf8")){
        echo '<p class="errore">Error: Unable to set the character set!</p>';
        exit;
    	}

      if(!$stmt = mysqli_prepare($conn->connection, $query)){
        die('prepare() failed: '.htmlspecialchars(mysqli_error($conn->$connection)));
      }

      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if($result->num_rows === 0){
      $errore = $errore_wrong;
      }
      else{
        $row = $result->fetch_assoc();
        $passwordCheck = password_verify($password, $row['password_utente']);
        /*inserimento solo tramite php cosi settiamo bcrypt come algoritmo!*/
        if($passwordCheck == false){
          $errore = $errore_wrong;
        }
        else{
          if(isset($_POST['remember_me'])){
            setcookie("email",$email,time()+60*60*24*30);
            setcookie("password",$password,time()+60*60*24*30);
          }
          $_SESSION['logged'] = true;
          $_SESSION['email_utente'] = $row['email_utente'];
          $_SESSION['tipo_utente'] = $row['tipo_utente'];
          header("location:index.php");
        }
        $stmt->close();
      }
      $conn->closeConnection();
    }

    $pagina = str_replace("%ERR_LOGIN%", $errore, $pagina);
    $pagina = str_replace("%LOGIN_STATUS%", $logged, $pagina);
    echo $pagina;
  }
  /*utente non ha premuto il tasto submit*/
  else{
    $pagina = str_replace("%ERR_LOGIN%", "", $pagina);
    $pagina = str_replace("%LOGIN_STATUS%", "", $pagina);
    echo $pagina;
  }
?>

<?php
    require_once("DBAccess.php");

    $pagina = file_get_contents('register.html');
    if($_POST['Registrati']){
      $email = mysql_real_escape_string(trim($_POST['email']));
      $password = mysql_real_escape_string(trim($_POST['password']));
      $minLengthPwd = 8;
      $maxLengthPwd = 12;
    }
?>

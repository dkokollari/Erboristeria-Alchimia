<?php
  session_start();
  if(isset($_SESSION['email_utente'])
  || (isset($_COOKIE['email']) && isset($_COOKIE['password']))){
    echo 'sei loggato';
  }
  else{
    echo'non sei loggato';
  }
?>

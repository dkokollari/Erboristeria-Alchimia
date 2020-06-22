<?php
  require_once("session.php");
  session_destroy();

  if(isset($_COOKIE['password']) && isset($_COOKIE['email'])) {
    setcookie("email", "", time()-3600);
    setcookie("password", "", time()-3600);
  }

  header("location: index.php");
  exit;
?>

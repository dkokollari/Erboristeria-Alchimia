<?php
  session_start();
  session_destroy();

  if(isset($_COOKIE['password']) && isset($_COOKIE['email'])) {
    setcookie("email", "", time()-3600);
    setcookie("password", "", time()-3600);
  }

  header("location: index.php");
  exit;
?>

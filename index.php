<?php
session_start();
if($_SESSION['logged'] == true) {
  echo 'sei loggato';
} else {
  echo'non sei loggato';
}
?>

<?php
  require_once("DBAccess.php");
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }

  $pagina = file_get_contents('prodotto_singolo.html');
?>

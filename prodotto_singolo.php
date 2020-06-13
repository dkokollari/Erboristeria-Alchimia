<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("Image.php");
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }

  $pagina = file_get_contents('prodotto_singolo.html');
    $meta_description = '';
    $img_1='';
    $img_2='';
    $desc_breve = '';
    $desc_completa = '';
    $ingredienti = '';
  $stmt = mysqli_prepare($conn->connection, $query_ricerca)

  if($_SESSION['utente'] == "utente_registrato") {
      //gestione carrello (bestemmie)
  } else {
      //sostituire tasto "aggiungi" con "<p>Crea un profilo per poter acquistare i nostri prodotti!</p>"
  }
?>

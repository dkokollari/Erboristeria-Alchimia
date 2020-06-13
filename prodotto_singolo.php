<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("Image.php");

  if(isset($_GET['id_articolo']) && is_numeric($_GET['id_articolo'])) {
    $id_articolo = (int)($_GET['page']) > 0 ? (int)$_GET['page'] : 1;
    $conn = new DBAccess();
    if(!$conn->openConnection()) {
     echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
     exit(1);
    }

    $pagina = file_get_contents('prodotto_singolo.html');
    $meta_description = '';
    $meta_keywords = '';
    $img_1 = '';
    $img_2 = '';
    $desc_breve = '';
    $desc_completa = '';
    $ingredienti = '';
    $row = '';

    $query = "SELECT nome_articolo, prezzo_articolo, sesso_target, nome_ditta, nome_linea, nome_categoria
    FROM articoli, ditte, produzioni, linee, categorie
    WHERE  id_articolo = '" . $id_articolo . "' AND articolo_produzione = id_articolo AND ditta_produzione = id_ditta
    AND id_linea = linea_articolo AND categoria_articolo = id_categoria";
    $result = mysqli_query($conn->connection, $query);
    if($result) { /*brutto if .... non ce l'else!!!!!!!!!!!!!!*/
      $row = $result->fetch_assoc();
    }/* else {
    printf("Error: %s\n", mysqli_error($conn->connection));
    break;
    }*/
    $meta_description = '<meta name="description" content="' . $row['nome_articolo'] .'"/>';
    $meta_keywords = '<meta name="keywords" content="' . str_replace('', ', ', $row['nome_articolo']) . ' alt=' . $row['nome_articolo']  . '"/>';
    $img_1 = '<img id="immagine_prodotto" src="' . Image::getImage("../img/products/", $row['id_articolo']) . ' alt=' . $row['nome_articolo']  .  '"/>';
    $img_2 = '<img id="topbar_image" src="' . Image::getImage("../img/products/", $row['id_articolo']) . '"/>';
    $desc_breve = '<p>' . $row['descrizione_articolo'] . '</p>'; //// WARNING: cè bisogno di un attributo 'descrizione breve' a db!
    $desc_completa = '<p>' . $row['descrizione_articolo'] . '</p>';
    $ingredienti =  '<p>' . $row['descrizione_articolo'] . '</p>'; /// WARNING: cè bisogno di un attributo 'preparazione' a db!

    if($_SESSION['utente'] == "utente_registrato") {
        //gestione carrello (bestemmie)
    } else {
        //sostituire tasto "aggiungi" con "<p>Crea un profilo per poter acquistare i nostri prodotti!</p>"
    }
}
?>

<?php

  /*-------------------INIZIO SESSIONE-----------------*/

  session_start();
  $product_ids = array();

  if(filter_input(INPUT_POST, 'add_to_cart')) {
    if(isset($_SESSION['shopping_cart'])) {
      $count = count($_SESSION['shopping_cart']);
      $product_ids = array_column($_SESSION['shopping_cart'], 'id_articolo');

      if(!in_array(filter_input(INPUT_GET, 'id_articolo'), $product_ids)) {
        $_SESSION['shopping_cart'][$count] = array
        (
          'id_articolo' => filter_input(INPUT_GET, 'id_articolo'),
      		'nome_articolo' => filter_input(INPUT_POST, 'nome_articolo'),
      		'prezzo_articolo' => filter_input(INPUT_POST, 'prezzo_articolo'),
      		'quantita' => filter_input(INPUT_POST, 'quantita')
        );
      } else {
        for($i=0; $i < count($product_ids); $i++) {
          if($product_ids[$i] == filter_input(INPUT_GET, 'id_articolo')) {
            $_SESSION['shopping_cart'][$i]['quantita'] += filter_input(INPUT_POST, 'quantita');
          }
        }
      }
    } else {
      $_SESSION['shopping_cart'][0] = array(
      'id_articolo' => filter_input(INPUT_GET, 'id_articolo'),
      'nome_articolo' => filter_input(INPUT_POST, 'nome_articolo'),
      'prezzo_articolo' => filter_input(INPUT_POST, 'prezzo_articolo'),
      'quantita' => filter_input(INPUT_POST, 'quantita')
      );
    }
    header('location:' . $_POST['redirect']);
  }

  if(filter_input(INPUT_GET, 'action') == 'delete') {
    foreach($_SESSION['shopping_cart'] as $key => $product) {
      if($product['id_articolo'] == filter_input(INPUT_GET, 'id_articolo')) {
        unset($_SESSION['shopping_cart'][$key]);
      }
    }
   $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
  }
/*-------------------------FINE SESSIONE(Meglio metterla in un file a parte!)----------------------------*/

require_once("DBAccess.php");
$pagina = file_get_contents('carrello.html');
$total = 0;
$orderedProducts = '';
if(!empty($_SESSION["shopping_cart"])) {
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }
  if (!$conn->connection->set_charset("utf8")) {
    //printf("Error loading character set utf8: %s\n", $con->error);
    exit(1);
  }


  $orderedProducts = '<ul>' . "\n";
  foreach($_SESSION["shopping_cart"] as $key => $product) {

    $query = "SELECT nome_articolo, prezzo_articolo, sesso_target, nome_ditta, nome_linea, nome_categoria
    FROM articoli, ditte, produzioni, linee, categorie
    WHERE  id_articolo = '" . $product["id_articolo"] . "' AND articolo_produzione = id_articolo AND ditta_produzione = id_ditta
    AND id_linea = linea_articolo AND categoria_articolo = id_categoria";
    $result = mysqli_query($conn->connection, $query);
    $row = '';
    if($result) { /*brutto if .... non ce l'else!!!!!!!!!!!!!!*/
      $row = $result->fetch_assoc();
    }/* else {
      printf("Error: %s\n", mysqli_error($conn->connection));
      break;
    }*/

    $orderedProducts .=
    '<li class="card_product">' . "\n" .
    '<img class="product_image" src="img/articoli/'.(file_exists("
     img/articoli/".$product["id_articolo"].".jpg") ? $product["id_articolo"].'.jpg' : '0.jpg').'" alt="immagine a scopo presentazionale"/>'."\n" .
      '<div class="product_description">' . "\n" .
        '<h3 class="product_title">' . $row["nome_articolo"] . '</h3>' . "\n" .
        '<span class="product_manufacturer">' . $row["nome_ditta"]
            . '</span>' . "\n" .
        '<span class="product_line">' . 'Linea ' . $row["nome_linea"]
           .'</span>' . "\n" .
        '<div class="product_tags">' . "\n" .
            '<span class="' . $row["nome_categoria"] . '">' . $row["nome_categoria"] . '</span>' . "\n" .
            '<span class="' . $row["sesso_target"] . '">' . $row["sesso_target"] . '</span>' . "\n" .
        '</div>' . "\n" .
        '<span class="product_price">Prezzo: ' . $row["prezzo_articolo"] . ' &euro;</span>' . "\n" .
      '</div>' . "\n" .
    '</li>' .  "\n";
  /*  '<span class="other">Quantit&agrave;: ' . $product["quantita"] . '</span>' . "\n" .
    '<span class="other">Totale: ' . number_format($product["quantita"] * $product["prezzo_articolo"], 2) . ' &euro;</span>' . "\n" .
    '<span>' . '<a href="carrello.php?action=delete&id_articolo=' . $product["id_articolo"] . '">' . "\n" .
    '<button class="button">Rimuovi</button>' . "\n" . '</a></span>' . "\n";*/
    $total += $product["quantita"] * $product["prezzo_articolo"];
  }
  $conn->closeConnection();

  $orderedProducts .= '</ul>' . '<span class="product_price" id="totale">Totale carrello: ' .  number_format($total, 2) . ' &euro;</span>'. "\n";

  if(isset($_SESSION['shopping_cart'])
      && count($_SESSION['shopping_cart']) > 0) {
        $orderedProducts .= '<a href="#" class="button" id="checkout">Checkout</a>'  . "\n";
  }
} else {
  $orderedProducts = '<p id="emptyCart">Il tuo carrello e\' vuoto: consulta la pagina dei nostri <a href= "articoli.php">prodotti</a>,
  potremmo avere qualcosa che fa per te!<p>';
}
$pagina = str_replace("%ORDERS%", $orderedProducts, $pagina);
echo $pagina;
?>

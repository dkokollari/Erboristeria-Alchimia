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
  }

  if(filter_input(INPUT_GET, 'action') == 'delete') {
    foreach($_SESSION['shopping_cart'] as $key => $product) {
      if($product['id_articolo'] == filter_input(INPUT_GET, 'id_articolo')) {
        unset($_SESSION['shopping_cart'][$key]);
      }
    }
   $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
  }

  /*

  DEBUG

  pre_r($_SESSION);

  function pre_r($array) {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
  }*/
/*-------------------FINE SESSIONE-----------------*/

  require_once("DBAccess.php");
  $pagina = file_get_contents('cart.html');
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }

  $productToPrint;
  $query = "SELECT * FROM articoli ORDER by id_articolo ASC";
  $result = mysqli_query($conn->connection, $query);
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      //print_r($row);
      $productToPrint .= '<div class = "col-sn-4 col-md-3">' . "\n" .
      '<form method="post" action="cart.php?action=add&id_articolo='. $row["id_articolo"] .  '">'. "\n" .
      '<div class="products">' . "\n" .
      '<img src="img/articoli/'.(file_exists("
      img/articoli/".$row["id_articolo"].".jpg") ? $row["id_articolo"].'.jpg' : '0.jpg').'" class="img-responsive"/>'."\n" .
      '<h4 class="text-info">' . $row["nome_articolo"] . '</h4>' . "\n" .
      '<h4>' . $row["prezzo_articolo"] . ' €' . '</h4>' ."\n" .
      '<input type="text" name="quantita" class="form-control" value="1" />' ."\n" .
      '<input type="hidden" name="nome_articolo" value="' . $row["nome_articolo"] . '"/>' . "\n" .
      '<input type="hidden" name="prezzo_articolo" value="' . $row["prezzo_articolo"] . '"/>' . "\n" .
       '<input type="submit" name="add_to_cart" class="btn btn-info CUSTOM_MARGIN" value="Add to Cart" />' . "\n" .
      '</div>' . "\n" . '</form>' . "\n" . '</div>' ."\n";
    }
  }
  $conn->closeConnection();

  $total = 0;
  $orderedProducts = "";
  if(!empty($_SESSION["shopping_cart"])) {
    foreach($_SESSION["shopping_cart"] as $key => $product) {
      $orderedProducts .= '<tr>' . '<td>' . $product["nome_articolo"] . '</td>' . "\n" .
      '<td>' . $product["quantita"] . '</td>' . "\n" .
      '<td>' . $product["prezzo_articolo"] . ' €</td>' . "\n" .
      '<td>' . number_format($product["quantita"] * $product["prezzo_articolo"], 2) . ' €</td>' . "\n" .
      '<td>' . '<a href="cart.php?action=delete&id_articolo=' . $product["id_articolo"] . '">' . "\n" .
      '<div class="btn-danger">Rimuovi</div>' . "\n" . '</a>' . "\n" . '</td>' . '</tr>' . "\n";
      $total += $product["quantita"] * $product["prezzo_articolo"];
    }

    $orderedProducts .= '<tr>' . "\n" . '<td colspan="3" align="right">Totale</td>' . "\n" .
    '<td align="right">' . number_format($total, 2) . ' €</td>' . "\n" .
    '<td></td>' . "\n" . '</tr>'  . "\n";

    $orderedProducts .= '<td colspan="5">'  . "\n";
    if(isset($_SESSION['shopping_cart'])
        && count($_SESSION['shopping_cart']) > 0) {
          $orderedProducts .= '<a href="#" class="button">Checkout</a>'  . "\n";
    }
    $orderedProducts .= '</td>' . "\n" . '</tr>' . "\n";
  }
  $pagina = str_replace("%PRODUCTS%", $productToPrint, $pagina);
  $pagina = str_replace("%ORDERS%", $orderedProducts, $pagina);
  echo $pagina;
?>

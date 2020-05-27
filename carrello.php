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
/*-------------------------FINE SESSIONE(Meglio metterla in un file a parte!)----------------------------*/

$pagina = file_get_contents('carrello.html');
$total = 0;
$orderedProducts = '<dl>';
if(!empty($_SESSION["shopping_cart"])) {
  foreach($_SESSION["shopping_cart"] as $key => $product) {
    $orderedProducts .= '<dt>' . $product["nome_articolo"] . '</dt>' . "\n" .
    '<img src="img/articoli/'.(file_exists("
    img/articoli/".$product["id_articolo"].".jpg") ? $row["id_articolo"].'.jpg' : '0.jpg').'" alt="immagine a scopo presentazionale"/>'."\n" .
    '<dd>' . "\n" .
        '<span>Quantit&agrave;: ' . $product["quantita"] . '</span>' . "\n" .
        '<span>Prezzo unitario: ' . $product["prezzo_articolo"] . ' €</span>' . "\n" .
        '<span>Prezzo totale: ' . number_format($product["quantita"] * $product["prezzo_articolo"], 2) . ' €</span>' . "\n" .
        '<span>' . '<a href="carrello.php?action=delete&id_articolo=' . $product["id_articolo"] . '">' . "\n" .
        '<button>Rimuovi</button>' . "\n" . '</a></span>' . "\n" .
    '</dd>' .  "\n";
    $total += $product["quantita"] * $product["prezzo_articolo"];
  }

  $orderedProducts .= '</dl>' . '<span id="totale">Totale: ' .  number_format($total, 2) . ' €</span>'. "\n";

  if(isset($_SESSION['shopping_cart'])
      && count($_SESSION['shopping_cart']) > 0) {
        $orderedProducts .= '<a href="#" class="button">Checkout</a>'  . "\n";
  }
}
$pagina = str_replace("%ORDERS%", $orderedProducts, $pagina);
echo $pagina;
?>

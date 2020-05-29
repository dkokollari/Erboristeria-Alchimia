<?php

  /*-------------------INIZIO SESSIONE-----------------*/

session_start();
$pagina = file_get_contents('carrello.html');
$orderedProducts = '';
if(isset($_SESSION['email_utente'])
    || (isset($_COOKIE['email']) && isset($_COOKIE['password']))) {
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

  $total = 0;
  if(!empty($_SESSION["shopping_cart"])) {
    foreach($_SESSION["shopping_cart"] as $key => $product) {
      $orderedProducts .= '<tr>' . '<td>' . $product["nome_articolo"] . '</td>' . "\n" .
      '<td>' . $product["quantita"] . '</td>' . "\n" .
      '<td>' . $product["prezzo_articolo"] . ' €</td>' . "\n" .
      '<td>' . number_format($product["quantita"] * $product["prezzo_articolo"], 2) . ' €</td>' . "\n" .
      '<td>' . '<a href="carrello.php?action=delete&id_articolo=' . $product["id_articolo"] . '">' . "\n" .
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
  $pagina = str_replace("%ORDERS%", $orderedProducts, $pagina);
  echo $pagina;
} else {
  /*$orderedProducts = '<p class = "errore">Solo gli utenti registrati possono accedere al carrello. Se
  sei registrato oppure vuoi creare un profilo sul nostro sito, <a href="login.php">clicca qui</a> </p>';*/
  /*TODO da fare in js il prepend alla pagina index.php di questo avviso!*/
  header('location: index.php');
}
?>

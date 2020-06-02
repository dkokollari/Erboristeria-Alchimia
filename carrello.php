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
$orderedProducts = '';
if(!empty($_SESSION["shopping_cart"])) {
  $orderedProducts = '<ul>' . "\n";
  foreach($_SESSION["shopping_cart"] as $key => $product) {
    $orderedProducts .=
    '<li class="card_product">' . "\n" .
    '<img class="product_image" src="img/articoli/'.(file_exists("
     img/articoli/".$product["id_articolo"].".jpg") ? $row["id_articolo"].'.jpg' : '0.jpg').'" alt="immagine a scopo presentazionale"/>'."\n" .
      '<div class="product_description">' . "\n" .
        '<h3 class="product_title">' . $product["nome_articolo"] . '</h3 class="product_title">' . "\n" .
        '<span class="product_manufacturer">Quantit&agrave;: ' . $product["quantita"] . '</span>' . "\n" .
        '<span class="product_manufacturer">Prezzo unitario: ' . $product["prezzo_articolo"] . ' &euro;</span>' . "\n" .
        '<span class="product_price">Totale: ' . number_format($product["quantita"] * $product["prezzo_articolo"], 2) . ' &euro;</span>' . "\n" .
        '<span>' . '<a href="carrello.php?action=delete&id_articolo=' . $product["id_articolo"] . '">' . "\n" .
        '<button class="button">Rimuovi</button>' . "\n" . '</a></span>' . "\n";
      '</div>' . "\n" .
    '</li>' .  "\n" .
    $total += $product["quantita"] * $product["prezzo_articolo"];
  }

/*
<li class="card_product">

  <img class="product_image"src="img/products/small_img/helan_linea_bimbi_amido_di_riso_min.jpg" alt="immagine linea bimbi amido di riso di Helan"/>

  <div class="product_description"> <!-- TODO : sarebbe da rendere un p, ma se metto p succede un casino -->
    <h3 class="product_title">Amido di riso</h3>
    <span class="product_manufacturer">Helan</span>
    <span class="product_line">Linea bimbi</span>

      <div class="product_tags">    <!-- TODO : trovare soluzione semantica (l'ideale sarebbe togliere il blocco) -->
        <span class="cosmetici">cosmetici</span>
        <span class="bimbi">bimbi</span>
      </div>
      <span class="product_price">10,50 &euro;</span>

  </div>
</li>
*/

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

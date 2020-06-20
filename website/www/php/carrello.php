<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("genera_pagina.php");

  $total = 0;
  $orderedProducts = '';
  if(!empty($_SESSION["shopping_cart"])) {
    $con = new DBAccess();
    if(!$con->openConnection()) {
      header('Location: redirect.php?error=3');
      exit;
    }

    $orderedProducts = '<ul class="adjust_margins">' . "\n";
    foreach($_SESSION["shopping_cart"] as $key => $product) {
      $query = "SELECT nome_articolo, prezzo_articolo, sesso_target, nome_ditta, nome_linea, nome_categoria
      FROM articoli, ditte, produzioni, linee, categorie
      WHERE  id_articolo = '" . $product["id_articolo"] . "' AND articolo_produzione = id_articolo AND ditta_produzione = id_ditta
      AND id_linea = linea_articolo AND categoria_articolo = id_categoria";
      $result = mysqli_query($con->connection, $query);
      $row = '';
      if($result) { // WARNING: se l'utente cambia querystring e mette id non esistente a db => salto quel valore e non creo schda prodotto!
      $row = $result->fetch_assoc();
      $orderedProducts .=
      '<li>' . "\n" .
        '<div class="card_product product_description">' . "\n" .
            '<img class="product_image" src="../img/articoli/'.(file_exists("
             ../img/articoli/".$product["id_articolo"].".jpg") ? $product["id_articolo"].'.jpg' : '0.jpg').'" alt="immagine a scopo presentazionale"/>'."\n" .
            '<h3 class="product_title">' . $row["nome_articolo"] . '</h3>' . "\n" .
            '<ul>' . "\n" .
                '<li class="product_manufacturer">' . $row["nome_ditta"] . '</li>' . "\n" .
                '<li class="product_line">' . 'Linea ' . $row["nome_linea"] .'</li>' . "\n" .
                '<li class="product_tags ' . $row["nome_categoria"] . '">' . $row["nome_categoria"] . '</li>' . "\n" .
                '<li class="product_tags ' . $row["sesso_target"] . '">' . $row["sesso_target"] . '</li>' . "\n" .
                '<li class="product_price">' . $row["prezzo_articolo"] . ' &euro;</li>' . "\n" .
            '</ul>' . "\n" .
        '</div>' . "\n" .
        '<ul class="recap_product">' . "\n" .
            '<li><abbr title="Quantit&agrave;">Q.ta : ' . $product["quantita"] . '</li>' . "\n" .
            '<li><abbr title="Totale">Tot</abbr>. : ' . number_format($product["quantita"] * $row["prezzo_articolo"], 2) . ' &euro;</li>' . "\n" .
            '<li>' . '<a href="aggiunta_rimozione_prodotti_carrello.php?action=delete&id_articolo=' . $product["id_articolo"] . '">' . "\n" .
            '<button class="button">Rimuovi</button>' . "\n" . '</a></li>' . "\n" .
        '</ul>'. "\n" .
      '</li>' .  "\n";
      $total += $row["prezzo_articolo"] * $product["quantita"];
      }
    }
    $con->closeConnection();

    $orderedProducts .= '</ul>' . '<span class="product_price">Totale carrello : ' .  number_format($total, 2) . ' &euro;</span>'. "\n";
    $orderedProducts .= '<a href="prodotto_singolo.php?addedProduct&amp;q.ta='.$_SESSION["shopping_cart"]["id_articolo"]["quantita"] . '&amp;id_articolo=' . $product["id_articolo"] . ' class="classic_btn" id="checkout">Checkout</a>'  . "\n";
    } // end if !empty($_SESSION["shopping_cart"])
    else {
      $orderedProducts = '<p id="emptyCart">Il tuo carrello e\' vuoto: consulta la pagina dei nostri <a href= "prodotti.php">prodotti</a>, potremmo avere qualcosa che fa per te!<p>';
    }
    $contenuto = file_get_contents("../html/carrello.html");
    $contenuto = str_replace("%ORDERS%", $orderedProducts, $contenuto);
    $pagina = Genera_pagina::genera("../html/base.html", "carrello", $contenuto);
    echo $pagina;
?>

<?php
  require_once('session.php');
  require_once('menu_pagina.php');

  $pagina = file_get_contents('base.html');
  $pagina = str_replace("%TITOLO_PAGINA%", "Prodotti", $pagina);
  $pagina = str_replace("%DESCRIZIONE_PAGINA%", 'I prodotti online di Erboristeria Alchimia. Qualità, sicurezza e convenienza garantiti', $pagina);
  $pagina = str_replace("%KEYWORDS_PAGINA%", 'prodotto, prodotti, cosmetici, alimentari, erboristeria, alchimia', $pagina);
  $pagina = str_replace("%CONTAINER_PAGINA%", "container_prodotti", $pagina);
  $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("prodotti.php"), $pagina);
  $pagina = str_replace("%CONTENUTO_PAGINA%", file_get_contents('prodotti.html'), $pagina);
  $pagina = ($_SESSION['auth'] && $_SESSION['tipo_utente']=="User"
            ? str_replace("%ICONA_CARRELLO%", '<span id="cart_icon" class="material-icons-outlined top_icon">shopping_cart</span>', $pagina)
            : str_replace("%ICONA_CARRELLO%", '', $pagina));
  echo $pagina;
?>

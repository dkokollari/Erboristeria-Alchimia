<?php
  require_once('session.php');
  require_once('menu_pagina.php');

  $pagina = file_get_contents('base.html');
  $pagina = str_replace("%TITOLO_PAGINA%", "Informazioni", $pagina);
  $pagina = str_replace("%DESCRIZIONE_PAGINA%", 'Informazioni utili per contattarci: qui trovi i nostri contatti, gli orari di apertura e il nostro indirizzo', $pagina);
  $pagina = str_replace("%KEYWORDS_PAGINA%", 'informazioni, orari, apertura, chiusura, email, mail, telefono, cellulare, posizione, mappa, erboristeria, alchimia', $pagina);
  $pagina = str_replace("%CONTAINER_PAGINA%", "container_informazioni", $pagina);
  $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("informazioni.php"), $pagina);
  $pagina = $_SESSION['auth'] ? str_replace("%ICONA_CARRELLO%",
                    '<span id="cart_icon" class="material-icons-outlined top_icon">shopping_cart</span>', $pagina)
                    : str_replace("%ICONA_CARRELLO%", '', $pagina);
  $pagina = str_replace("%CONTENUTO_PAGINA%", file_get_contents('informazioni.html'), $pagina);
  echo $pagina;
?>

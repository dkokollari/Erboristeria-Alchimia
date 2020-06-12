<?php
  require_once('menu_pagina.php');

  $pagina = file_get_contents('base.html');
  $pagina = str_replace("%TITOLO_PAGINA%", "Informazioni", $pagina);
  $pagina = str_replace("%DESCRIZIONE_PAGINA%", '<meta name="description" content="Informazioni utili per contattarci: qui trovi i nostri contatti, gli orari di apertura e il nostro indirizzo"/>', $pagina);
  $pagina = str_replace("%KEYWORDS_PAGINA%", '<meta name="keywords" content="informazioni, orari, apertura, chiusura, email, mail, telefono, cellulare, posizione, mappa, erboristeria, alchimia"/>', $pagina);
  $pagina = str_replace("%CONTAINER_PAGINA%", "container", $pagina);
  $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("informazioni.php"), $pagina);
  $pagina = str_replace("%CONTENUTO_PAGINA%", file_get_contents('informazioni.html'), $pagina);
  echo $pagina;
?>

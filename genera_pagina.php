<?php
  require_once("session.php");
  require_once("menu_pagina.php");

  class Genera_pagina {

    public function genera($base, $target, $contenuto="") {
    $pagina = file_get_contents($base);
    switch($target) {
      case "carrello" :
      break;

      case "eventi" :
      break;

      case "form_eventi" :
      break;

      case "index" :
      break;

      case "informazioni" :
      break;

      case "la_mia_storia" :
      break;

      case "prodotti" :
      break;

      case "profilo" :
      break;

      case "registrazione" :
      break;
      
      case "teinfusi" :
        $titolo = "T&egrave; &amp; Infusi";
        $titolo_pagina = "T&egrave; e infusi di Erboristeria Alchimia";
        $descrizione_pagina = "Visualizza i nostri te e infusi";
        $keywords_pagina = "te, infusi, te e infusi, erboristeria, alchimia";
        $container_pagina = "container_te_e_infusi";
        $lista_menu = menu_pagina::menu("teinfusi.php");
        if($_SESSION['auth'] && $_SESSION['tipo_utente']=="User") {
          $icona_carrello = '<span id="cart_icon" class="material-icons-outlined top_icon">shopping_cart</span>';
        }
      break;
    }
    $pagina = str_replace("%TITOLO%", $titolo, $pagina);
    $pagina = str_replace("%TITOLO_PAGINA%", $titolo_pagina, $pagina);
    $pagina = str_replace("%DESCRIZIONE_PAGINA%", $descrizione_pagina, $pagina);
    $pagina = str_replace("%KEYWORDS_PAGINA%", $keywords_pagina, $pagina);
    $pagina = str_replace("%CONTAINER_PAGINA%", $container_pagina, $pagina);
    $pagina = str_replace("%LISTA_MENU%", $lista_menu, $pagina);
    $pagina = str_replace("%ICONA_CARRELLO%", $icona_carrello, $pagina);
    $pagina = str_replace("%CONTENUTO_PAGINA%", $contenuto, $pagina);
    return $pagina;
    }

  }
?>

<?php
  require_once("session.php");
  require_once("menu_pagina.php");

  class Genera_pagina {

    public function genera($base, $target, $contenuto="") {
      $pagina = file_get_contents($base);
      // impostazione icona del carrello
      if($_SESSION['auth']) {
        if($_SESSION['tipo_utente']=="User") {
          $icona_top = '<span id="cart_icon" class="material-icons-outlined top_icon">shopping_cart</span>';
        }
        elseif($_SESSION['tipo_utente']=="Admin") {
          $icona_top = ''; // TODO: aggiungere icona "pannello gestione utenti"
        }
      }

      // pagine generabili
      switch($target) {
        case "carrello" :
          $titolo = "Carrello";
          $titolo_pagina = "Carrello di Erboristeria Alchimia";
          $descrizione_pagina = "Il tuo carrello verde made by Erboristeria Alchimia";
          $keywords_pagina = "carrello, acquista, acquisti, erboristeria, alchimia";
          $link_head = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>';
          $container_pagina = "container_prodotti"; // TODO: controllare  effetti su CSS usando container_carrello
          $attributi_body = 'id="carrello"';
          $icona_top = "";
          $lista_menu = menu_pagina::menu($target);
        break;

        case "eventi" :
          $titolo = "Eventi";
          $titolo_pagina = "Eventi di Erboristeria Alchimia";
          $descrizione_pagina = "Qui troverai i prossimi eventi in programma e quelli passati organizzati";
          $keywords_pagina = "eventi, evento, relatori, relatore, organizzazione, organizza, erboristeria, alchimia";
          $container_pagina = "container_te_e_infusi"; // TODO: controllare  effetti su CSS usando container_eventi
          $lista_menu = menu_pagina::menu($target);
        break;

        case "form_eventi" :
          $titolo = "Nuovo evento";
          $titolo_pagina = "Nuovo evento di Erboristeria Alchimia";
          $descrizione_pagina = "Form di inserimento nuovo evento";
          $keywords_pagina = "inserimento, evento, form, nuovo, erboristeria, alchimia";
          $container_pagina = "container"; // TODO: controllare  effetti su CSS usando container_form_eventi
          $lista_menu = menu_pagina::menu();
        break;

        case "form_teinfusi" :
          $titolo = "Nuovo Te & Infusi";
          $titolo_pagina = "Nuovo te & infusi di Erboristeria Alchimia";
          $descrizione_pagina = "Form di inserimento e modifica di un t&egrave; o infuso";
          $keywords_pagina = "inserimento, modifica, form, t&egrave;, infusi, infuso, erboristeria, alchimia";
          $container_pagina = "container"; // TODO: controllare  effetti su CSS usando container_form_teinfusi
          $lista_menu = menu_pagina::menu();
        break;

        case "index" :
        break;

        case "informazioni" :
          $titolo = "Informazioni";
          $titolo_pagina = "Informazioni di Erboristeria Alchimia";
          $descrizione_pagina = "Informazioni utili per contattarci: qui trovi i nostri contatti, gli orari di apertura e il nostro indirizzo";
          $keywords_pagina = "informazioni, orari, apertura, chiusura, email, mail, telefono, cellulare, posizione, mappa, erboristeria, alchimia";
          $container_pagina = "container_informazioni";
          $lista_menu = menu_pagina::menu($target);
        break;

        case "la_mia_storia" :
          $titolo = "La mia storia";
          $titolo_pagina = "La mia storia di Erboristeria Alchimia";
          $descrizione_pagina = "Dove e come siamo nati, tutti i dettagli e le curiosità di Erboristeria Alchimia";
          $keywords_pagina = "storia, Marika, erboristeria, alchimia";
          $container_pagina = "container_la_mia_storia";
          $lista_menu = menu_pagina::menu($target);
        break;

        case "prodotti" :
          $titolo = "Prodotti";
          $titolo_pagina = "Prodotti di Erboristeria Alchimia";
          $descrizione_pagina = "I prodotti online di Erboristeria Alchimia. Qualità, sicurezza e convenienza garantiti";
          $keywords_pagina = "prodotto, prodotti, cosmetici, alimentari, erboristeria, alchimia";
          $container_pagina = "container_prodotti";
          $lista_menu = menu_pagina::menu($target);
        break;

        case "profilo" :
          $titolo = "Profilo";
          $titolo_pagina = "Gestione del profilo di Erboristeria Alchimia";
          $descrizione_pagina = "Pagina di gestione delle informazioni del tuo profilo";
          $keywords_pagina = "profilo, informazioni, personali, erboristeria, alchimia";
          $container_pagina = "container_il_tuo_profilo";
          $lista_menu = menu_pagina::menu($target);
        break;

        case "registrazione" :
          $titolo = "Registrati";
          $titolo_pagina = "Registrati ad Erboristeria Alchimia";
          $descrizione_pagina = "Pagina di registrazione al sito";
          $keywords_pagina = "registrazione, email, password, erboristeria, alchimia";
          $script_body = '<script src="../javascript/validate_form.js"></script>
                          <script src="../javascript/date-input-polyfill.dist.js"></script>';
          // $container_pagina = "log_in_box_container"; // TODO: controllare  effetti su CSS usando container_redirect
          $lista_menu = menu_pagina::menu();
        break;

        case "redirect" :
          $titolo = "Reindirizzamento";
          $titolo_pagina = "Reindirizzamento di Erboristeria Alchimia";
          $descrizione_pagina = "Sembra esserci stato un errore, questa &egrave; una pagina di reindirizzamento";
          $keywords_pagina = "reindirizzamento, redirect, errore, errori, erboristeria, alchimia";
          $container_pagina = "container"; // TODO: controllare  effetti su CSS usando container_redirect
          $lista_menu = menu_pagina::menu();
        break;

        case "teinfusi" :
          $titolo = "T&egrave; &amp; Infusi";
          $titolo_pagina = "T&egrave; e infusi di Erboristeria Alchimia";
          $descrizione_pagina = "Visualizza i nostri te e infusi";
          $keywords_pagina = "te, infusi, te e infusi, erboristeria, alchimia";
          $container_pagina = "container_te_e_infusi";
          $lista_menu = menu_pagina::menu($target);
        break;
      }
      $pagina = str_replace("%TITOLO%", $titolo, $pagina);
      $pagina = str_replace("%TITOLO_PAGINA%", $titolo_pagina, $pagina);
      $pagina = str_replace("%DESCRIZIONE_PAGINA%", $descrizione_pagina, $pagina);
      $pagina = str_replace("%KEYWORDS_PAGINA%", $keywords_pagina, $pagina);
      $pagina = str_replace("%META_HEAD%", $meta_head, $pagina);
      $pagina = str_replace("%LINK_HEAD%", $link_head, $pagina);
      $pagina = str_replace("%SCRIPT_HEAD%", $script_head, $pagina);
      $pagina = str_replace("%ATTRIBUTI_BODY%", $attributi_body, $pagina);
      $pagina = str_replace("%CONTAINER_PAGINA%", $container_pagina, $pagina);
      $pagina = str_replace("%ICONA_TOP%", $icona_top, $pagina);
      $pagina = str_replace("%LISTA_MENU%", $lista_menu, $pagina);
      $pagina = str_replace("%CONTENUTO_PAGINA%", $contenuto, $pagina);
      $pagina = str_replace("%SCRIPT_BODY%", $script_body, $pagina);
      return $pagina;
    }

  }
?>

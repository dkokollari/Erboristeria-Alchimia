<?php
  require_once("session.php");

  class menu_pagina {
    public static function menu($target="") {
      $menu =  '<li><a href="../front/index.php" xml:lang="en">homepage</a></li>
                <li><a href="../front/teinfusi.php">t&egrave; &amp; infusi</a></li>
                <li><a href="../front/prodotti.php">prodotti</a></li>
                <li><a href="../front/eventi.php">eventi</a></li>
                <li><a href="../front/la_mia_storia.php">la mia storia</a></li>
                <li><a href="../front/informazioni.php">informazioni</a></li>';

      if(!$_SESSION['auth']) $menu .= '<li><a href="../front/login.php">accedi</a></li>';
      else {
        if($_SESSION['tipo_utente']=='User') $menu .= '<li><a href="../front/carrello.php">carrello</a></li>';
        $menu .= '<li><a href="../front/profilo.php">profilo</a></li>
                  <li><a href="logout.php" xml:lang="en">logout</a></li>';
      }
      return menu_pagina::paginaCorrente($target, $menu);
    }

    private static function paginaCorrente($target, $menu) {
      if(!empty($target)) {
        switch($target) {
          case "carrello" :
            $menu = str_replace('<li><a href="../front/carrello.php">carrello</a></li>',
                                '<li class="selected_page">carrello</li>', $menu);
          break;

          case "eventi" :
            $menu = str_replace('<li><a href="../front/eventi.php">eventi</a></li>',
                                '<li class="selected_page">eventi</li>', $menu);
          break;

          case "index" :
            $menu = str_replace('<li><a href="../front/index.php" xml:lang="en">homepage</a></li>',
                                '<li class="selected_page"><span xml:lang="en">homepage</span></li>', $menu);
          break;

          case "informazioni" :
            $menu = str_replace('<li><a href="../front/informazioni.php">informazioni</a></li>',
                                '<li class="selected_page">informazioni</li>', $menu);
          break;

          case "la_mia_storia" :
            $menu = str_replace('<li><a href="../front/la_mia_storia.php">la mia storia</a></li>',
                                '<li class="selected_page">la mia storia</li>', $menu);
          break;

          case "prodotti" :
            $menu = str_replace('<li><a href="../front/prodotti.php">prodotti</a></li>',
                                '<li class="selected_page">prodotti</li>', $menu);
          break;

          case "teinfusi" :
            $menu = str_replace('<li><a href="../front/teinfusi.php">t&egrave; &amp; infusi</a></li>',
                                '<li class="selected_page">t&egrave; &amp; infusi</li>', $menu);
          break;

          case "profilo" :
            $menu = str_replace('<li><a href="../front/profilo.php">profilo</a></li>',
                                '<li class="selected_page">profilo</li>', $menu);
          break;
        }
      }
      return $menu;
    }

  }
?>

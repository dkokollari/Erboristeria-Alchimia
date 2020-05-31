<?php
require_once('session.php');

class menu_pagina{

  public static function menu($pagina)
  {
    $menu ='';
    if($pagina=="teinfusi.php"){
      $menu = '<li><a href="homepage.html" xml:lang="en">Homepage</a></li>
          <li><a href="eventi.html">Eventi</a></li>
          <li class="selected_page"><p>T&egrave; &amp; Infusi</p></li>
          <li><a href="prodotti.html">Prodotti</a></li>
          <li><a href="informazioni.html">Informazioni</a></li>
          <li><a href="la_mia_storia.html">La mia Storia</a></li>';
    }
    else if($pagina=="homepage.php"){
      $menu = '<li class="selected_page"><span xml:lang="en">Homepage</span></li>
          <li><a href="eventi.html">Eventi</a></li>
          <li><a href="teinfusi.php">T&egrave; &amp; Infusi</a></li>
          <li><a href="prodotti.html">Prodotti</a></li>
          <li><a href="informazioni.html">Informazioni</a></li>
          <li><a href="la_mia_storia.html">La mia Storia</a></li>';
    }
    else if($pagina=="eventi.php"){
      $menu = '<li><a href="homepage.html"><span xml:lang="en">Homepage</span></a></li>
          <li class="selected_page">Eventi</li>
          <li><a href="teinfusi.php">T&egrave; &amp; Infusi</a></li>
          <li><a href="prodotti.html">Prodotti</a></li>
          <li><a href="informazioni.html">Informazioni</a></li>
          <li><a href="la_mia_storia.html">La mia Storia</a></li>';
    }
    else if($pagina=="prodotti.php"){
      $menu = '<li><a href="homepage.html"><span xml:lang="en">Homepage</span></a></li>
          <li><a href="eventi.html">Eventi</a></li>
          <li><a href="teinfusi.php">T&egrave; &amp; Infusi</a></li>
          <li class="selected_page">Prodotti</li>
          <li><a href="informazioni.html">Informazioni</a></li>
          <li><a href="la_mia_storia.html">La mia Storia</a></li>';
    }
    else if($pagina=="informazioni.php"){
      $menu = '<li><a href="homepage.html"><span xml:lang="en">Homepage</span></a></li>
          <li><a href="eventi.html">Eventi</a></li>
          <li><a href="teinfusi.php">T&egrave; &amp; Infusi</a></li>
          <li><a href="prodotti.html">Prodotti</a></li>
          <li class="selected_page">Informazioni</li>
          <li><a href="la_mia_storia.html">La mia Storia</a></li>';
    }
    else if($pagina=="la_mia_storia.php"){
      $menu = '<li><a href="homepage.html"><span xml:lang="en">Homepage</span></a></li>
          <li><a href="eventi.html">Eventi</a></li>
          <li><a href="teinfusi.php">T&egrave; &amp; Infusi</a></li>
          <li><a href="prodotti.html">Prodotti</a></li>
          <li><a href="informazioni.html">Informazioni</a></li>
          <li class="selected_page">La mia Storia</li>';
    }
    else{
      $menu = '<li><a href="homepage.html"><span xml:lang="en">Homepage</span></a></li>
          <li><a href="eventi.html">Eventi</a></li>
          <li><a href="teinfusi.php">T&egrave; &amp; Infusi</a></li>
          <li><a href="prodotti.html">Prodotti</a></li>
          <li><a href="informazioni.html">Informazioni</a></li>
          <li><a href="la_mia_storia.html">La mia Storia</a></li>';
    }

    return $menu;

  }
}


 ?>

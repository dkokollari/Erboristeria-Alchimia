<?php
require_once('session.php');

class menu_pagina{

  public static function menu($pagina)
  {
    $menu ='<li><a href="home.php" xml:lang="en">home</a></li>
              <li><a href="teinfusi.php">t&egrave; &amp; infusi</a></li>
              <li><a href="prodotti.php">prodotti</a></li>
              <li><a href="eventi.php">eventi</a></li>
              <li><a href="la_mia_storia.php">la mia storia</a></li>
              <li><a href="informazioni.php">informazioni</a></li>';

    if(isset($_SESSION['auth']) && !$_SESSION['auth']){
      $menu .= '<li><a href="login.php">accedi</a></li>';
    }
    else{

      if($_SESSION['utente']=='User'){
            $menu .= '<li><a href="carrello.php">carrello</a></li>';
      }

      $menu .= '<li><a href="profilo.php">profilo</a></li>
                <li><a href="loggout.php" xml:lang="en">loggout</a></li>';
    }

    return menu_pagina::paginaCorrente($pagina, $menu);
  }

  private static function paginaCorrente($pagina, $menu){

    if($pagina=="home.php"){
      $menu = str_replace('<li><a href="home.php" xml:lang="en">home</a></li>',
                    '<li class="selected_page"><span xml:lang="en">home</span></li>',$menu);
    }

    else if($pagina=="teinfusi.php"){
      $menu = str_replace('<li><a href="teinfusi.php">t&egrave; &amp; infusi</a></li>',
                    '<li class="selected_page">t&egrave; &amp; infusi</li>',$menu);
    }

    else if($pagina=="prodotti.php"){
      $menu = str_replace('<li><a href="prodotti.php">prodotti</a></li>',
                    '<li class="selected_page">prodotti</li>',$menu);
    }

    else if($pagina=="eventi.php"){
      $menu = str_replace('<li><a href="eventi.php">eventi</a></li>',
                    '<li class="selected_page">eventi</li>',$menu);
    }

    else if($pagina=="la_mia_storia.php"){
      $menu = str_replace('<li><a href="la_mia_storia.php">la mia storia</a></li>',
                    '<li class="selected_page">la mia storia</li>',$menu);
    }

    else if($pagina=="informazioni.php"){
      $menu = str_replace('<li><a href="informazioni.php">informazioni</a></li>',
                    '<li class="selected_page">informazioni</li>',$menu);
    }

    else if($pagina=="carrello.php"){
      $menu = str_replace('<li><a href="carrello.php">carrello</a></li>',
                    '<li class="selected_page">carrello</li>',$menu);
    }

    else if($pagina=="profilo.php"){
      $menu = str_replace('<li><a href="profilo.php">profilo</a></li>',
                    '<li class="selected_page">profilo</li>',$menu);
    }

    return $menu;

  }



}


 ?>

<?php
require_once ("session.php");

class menu_pagina
{
  public static function menu($target = "")
  {
    $menu = '<li><a href="index.php" xml:lang="en">Homepage</a></li>
               <li><a href="teinfusi.php">T&egrave; &amp; infusi</a></li>
               <li><a href="prodotti.php">Prodotti</a></li>
               <li><a href="eventi.php">Eventi</a></li>
               <li><a href="la_mia_storia.php">La mia storia</a></li>
               <li><a href="informazioni.php">Informazioni</a></li>';

    if (!$_SESSION['auth']) $menu .= '<li><a href="login.php">Accedi</a></li>';
    else
    {
      if ($_SESSION['tipo_utente'] == 'User') $menu .= '<li><a href="carrello.php">Carrello</a></li>';
      $menu .= '<li><a href="profilo.php">Profilo</a></li>
                  <li><a href="logout.php" xml:lang="en">Logout</a></li>';
    }
    return menu_pagina::paginaCorrente($target, $menu);
  }

  private static function paginaCorrente($target, $menu)
  {
    if (!empty($target))
    {
      switch ($target)
      {
        case "carrello":
          $menu = str_replace('<li><a href="carrello.php">Carrello</a></li>', '<li class="selected_page">Carrello</li>', $menu);
        break;

        case "eventi":
          $menu = str_replace('<li><a href="eventi.php">Eventi</a></li>', '<li class="selected_page">Eventi</li>', $menu);
        break;

        case "index":
          $menu = str_replace('<li><a href="index.php" xml:lang="en">Homepage</a></li>', '<li class="selected_page"><span xml:lang="en">Homepage</span></li>', $menu);
        break;

        case "informazioni":
          $menu = str_replace('<li><a href="informazioni.php">Informazioni</a></li>', '<li class="selected_page">Informazioni</li>', $menu);
        break;

        case "la_mia_storia":
          $menu = str_replace('<li><a href="la_mia_storia.php">La mia storia</a></li>', '<li class="selected_page">La mia storia</li>', $menu);
        break;

        case "login":
          $menu = str_replace('<li><a href="login.php">Accedi</a></li>', '<li class="selected_page">Accedi</li>', $menu);
        break;

        case "prodotti":
          $menu = str_replace('<li><a href="prodotti.php">Prodotti</a></li>', '<li class="selected_page">Prodotti</li>', $menu);
        break;

        case "profilo":
          $menu = str_replace('<li><a href="profilo.php">Profilo</a></li>', '<li class="selected_page">Profilo</li>', $menu);
        break;

        case "teinfusi":
          $menu = str_replace('<li><a href="teinfusi.php">T&egrave; &amp; infusi</a></li>', '<li class="selected_page">T&egrave; &amp; infusi</li>', $menu);
        break;
      }
    }
    return $menu;
  }

}
?>

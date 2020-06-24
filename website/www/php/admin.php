<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("genera_pagina.php");
  require_once("Utilities.php");

  // if($_SESSION['auth'] && $_SESSION['tipo_utente'] == "Admin") {
    if(isset($_GET['search'])) {
      $email_search = mysql_real_escape_string(trim($_GET['email_search']));
      $ordina_utenti = mysql_real_escape_string(trim($_GET['ordina_utenti']));
      $numero_risultati = mysql_real_escape_string(trim($_GET['numero_risultati']));
      $page = Utilities::getNumericValue('page');
      $start = (($page > 1)
                ? $page * $numero_risultati - $numero_risultati
                : 0);

      $con = new DBAccess();
      if(!$con->openConnection()) {
        header('Location: redirect.php?error=1');
        exit;
      }
      else {
        $lista_utenti = $con->getUtenti(true, $email_search, $ordina_utenti, $start, $numero_risultati);

        $immagine_utente_src = "../icons/person_outline-24px.svg";
        foreach ($lista_utenti as $row) {
          $email = $row["email_utente"];
          $nome = htmlentities($row["nome_utente"]);
          $cognome = htmlentities($row["cognome_utente"]);
          $data_nascita = date('d-m-Y' , strtotime($row["data_nascita_utente"]));
          $data_registrazione = date('d-m-Y H:i:s' , strtotime($row["data_registrazione_utente"]));
          $tipo = $row["tipo_utente"];
          $timbri = $row["numero_timbri_utente"];
          $utenti .= '<li class="card_product product_description">
                        <img class="product_image" src="'.$immagine_utente_src.'" alt="Immagine utente"/>
                        <h3 class="product_title">'.$email.'</h3>
                        <ul>
                          <li>'.$nome.'</li>
                          <li>'.$cognome.'</li>
                          <li>'.$data_nascita.'</li>
                          <li>'.$data_registrazione.'</li>
                          <li>'.$tipo.'</li>
                          <li>'.$timbri.' timbri</li>
                        </ul>
                        <span class="material-icons">edit</span>
                        <span class="material-icons">delete</span>
                      </li>
                      ';
        } // end foreach $lista_utenti as $row
      } // end else $con->openConnection()
    } // end if isset($_GET['search'])



    $contenuto = file_get_contents("../html/admin.html");
    $contenuto = str_replace("%UTENTI%", $utenti, $contenuto);
    $contenuto = str_replace("%PAGES_MENU%", $pages_menu, $contenuto);
    $pagina = Genera_pagina::genera("../html/base5.html", "admin", $contenuto);
    echo $pagina;
  // } // end if $_SESSION['auth'] && $_SESSION['tipo_utente'] == "Admin"
  // else {
  //   header('Location: redirect.php?error=3');
  //   exit;
  // }
?>

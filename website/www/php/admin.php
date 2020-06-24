<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("genera_pagina.php");
  require_once("Utilities.php");

  $contenuto = file_get_contents("../html/admin.html");

  // if($_SESSION['auth'] && $_SESSION['tipo_utente'] == "Admin") {
    if(isset($_POST['edit'])) {
      $con = new DBAccess();
      if(!$con->openConnection()) {
        header('Location: redirect.php?error=1');
        exit;
      }
      else {
        $con->updateTimbriSingolo_Utenti($_POST['hidden_email'], $_POST['new_timbri']);
        $con->closeConnection();
      }
    }
    else if(isset($_POST['delete'])) {
      $con = new DBAccess();
      if(!$con->openConnection()) {
        header('Location: redirect.php?error=1');
        exit;
      }
      else {
        $con->deleteUtenti($_POST['hidden_email']);
        $con->closeConnection();
      }
    }
    if(!isset($_GET['search'])) {
      header('Location: admin.php?email_search=&ordina_utenti=&numero_risultati=20&search=Invia');
      exit;
    }
    else {
      $email_search = mysql_real_escape_string(trim($_GET['email_search']));
      $ordina_utenti = mysql_real_escape_string(trim($_GET['ordina_utenti']));
      $numero_risultati = mysql_real_escape_string(trim($_GET['numero_risultati']));
      $page = Utilities::getNumericValue('page');
      $start = (($page > 1)
                ? $page * $numero_risultati - $numero_risultati
                : 0);

      // sezione memorizza scelta combobox
      switch($ordina_utenti) {
        case "" :
          $contenuto = str_replace('<option value="">&nbsp;</option>',
                                   '<option selected="selected" value="">&nbsp;</option>', $contenuto);
        break;
        case "timbri" :
          $contenuto = str_replace('<option value="timbri">Timbri</option>',
                                   '<option selected="selected" value="timbri">Timbri</option>', $contenuto);
        break;
        case "tipo_utente" :
          $contenuto = str_replace('<option value="tipo_utente">Tipo utente</option>',
                                   '<option selected="selected" value="tipo_utente">Tipo utente</option>', $contenuto);
        break;
        case "data_nascita_ASC" :
          $contenuto = str_replace('<option value="data_nascita_ASC">Data nascita ASC</option>',
                                   '<option selected="selected" value="data_nascita_ASC">Data nascita ASC</option>', $contenuto);
        break;
        case "data_nascita_DESC" :
          $contenuto = str_replace('<option value="data_nascita_DESC">Data nascita DESC</option>',
                                   '<option selected="selected" value="data_nascita_DESC">Data nascita DESC</option>', $contenuto);
        break;
        case "data_registrazione_ASC" :
          $contenuto = str_replace('<option value="data_registrazione_ASC">Data registrazione ASC</option>',
                                   '<option selected="selected" value="data_registrazione_ASC">Data registrazione ASC</option>', $contenuto);
        break;
        case "data_registrazione_DESC" :
          $contenuto = str_replace('<option value="data_registrazione_DESC">Data registrazione DESC</option>',
                                   '<option selected="selected" value="data_registrazione_DESC">Data registrazione DESC</option>', $contenuto);
        break;
      };
      switch($numero_risultati) {
        case "20" :
          $contenuto = str_replace('<option value="20">20</option>',
                                   '<option selected="selected" value="20">20</option>', $contenuto);
        break;
        case "40" :
          $contenuto = str_replace('<option value="40">40</option>',
                                   '<option selected="selected" value="40">40</option>', $contenuto);
        break;
        case "80" :
          $contenuto = str_replace('<option value="80">80</option>',
                                   '<option selected="selected" value="80">80</option>', $contenuto);
        break;
      };

      // sezione ottenimento utenti
      $con = new DBAccess();
      if(!$con->openConnection()) {
        header('Location: redirect.php?error=1');
        exit;
      }
      else {
        $lista_utenti = $con->getUtenti(true, $email_search, $ordina_utenti, $start, $numero_risultati);

        $immagine_utente_src = "../icons/person_outline-24px.svg";
        $utenti = '<ul>';
        foreach ($lista_utenti as $row) {
          $email = $row["email_utente"];
          $nome = htmlentities($row["nome_utente"]);
          $cognome = htmlentities($row["cognome_utente"]);
          $data_nascita = date('d-m-Y', strtotime($row["data_nascita_utente"]));
          $data_registrazione = date('d-m-Y H:i:s', strtotime($row["data_registrazione_utente"]));
          $tipo = $row["tipo_utente"];
          $timbri = $row["numero_timbri_utente"];
          $utenti .= '<li class="card_product product_description card_user">
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
                        <form action="admin.php" method="POST">
                          <input type="hidden" name="hidden_email" value="'.$email.'"/>
                          <input type="text" name="new_timbri" placeholder="Numero timbri" title="Nuovo numero timbri"/>
                          <input type="submit" name="edit" value="edit" class="material-icons"/>
                          <input type="submit" name="delete" value="delete" class="material-icons"/>
                        </form>
                      </li>
                      ';
        } // end foreach $lista_utenti as $row
        $utenti .= '</ul>';

        // sezione numeretti pagine
        $total_records = $con->getRows();
        $total_records = $total_records[0]["total"];
        $con->closeConnection();
        $total_pages = ceil($total_records / $numero_risultati);
        if($page > 1) {
          $pages_menu = '<a href="admin.php?page='.($page-1)."&amp;email_search=$email_search&amp;ordina_utenti=$ordina_utenti".
          "&amp;numero_risultati=$numero_risultati&amp;search=Invia".'" class="classic_btn always_visible">Indietro</a>'."\n";
        }
        for($n_page = 1; $n_page <= $total_pages; $n_page++) {
          if($n_page != $page && ($n_page > ($page+1) || $n_page < ($page-1))) {
            $pages_menu .= '<a href="admin.php?page='.$n_page."&amp;email_search=$email_search&amp;ordina_utenti=$ordina_utenti".
            "&amp;numero_risultati=$numero_risultati&amp;search=Invia".'" class="classic_btn hidden">'.$n_page.'</a>'."\n";
          }
          else if($n_page != $page) {
            $pages_menu .= '<a href="admin.php?page='.$n_page."&amp;email_search=$email_search&amp;ordina_utenti=$ordina_utenti".
            "&amp;numero_risultati=$numero_risultati&amp;search=Invia".'" class="classic_btn">'.$n_page.'</a>'."\n";
          }
          else {
            $pages_menu .= '<span class="classic_btn selected">'.$page.'</span>';
          }
        }
        if($page < $total_pages) {
          $pages_menu .= '<a href="admin.php?page='.($page+1)."&amp;email_search=$email_search&amp;ordina_utenti=$ordina_utenti".
          "&amp;numero_risultati=$numero_risultati&amp;search=Invia".'" class="classic_btn always_visible">Avanti</a>'."\n";
        }
      } // end else $con->openConnection()
    } // end else isset($_GET['search'])

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

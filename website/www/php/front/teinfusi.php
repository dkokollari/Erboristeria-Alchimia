<?php
  require_once("website/www/php/back/session.php");
  require_once("website/www/php/back/DBAccess.php");
  require_once("website/www/php/back/Image.php");
  require_once("website/www/php/back/genera_pagina.php");

  $con = new DBAccess();
  if($con->openConnection()) {
    $lista_te_e_infusi = $con->getTeInfusi();

    foreach ($lista_te_e_infusi as $row) {
      $id = $row["id_te_e_infusi"];
      $immagine = Image::getImage("website/www/img/te_e_infusi/", $id);
      $descrizione_immagine = htmlentities($row["descrizione_immagine_te_e_infusi"]);
      $nome = htmlentities($row["nome_te_e_infusi"]);
      $ingredienti = DBAccess::nl2p(htmlentities($row["ingredienti_te_e_infusi"]));
      $descrizione = DBAccess::nl2p(htmlentities($row["descrizione_te_e_infusi"]));
      $preparazione = DBAccess::nl2p(htmlentities($row["preparazione_te_e_infusi"]));
      $logged_admin = (($_SESSION['auth'] && $_SESSION['tipo_utente']=="Admin")
                       ? true
                       : false);

      $lista .=
        '<div class="card collapsed" title="'.$nome.'. Premi per espandere la descrizione">
          <h3>'.$nome.'</h3>
          <a class="accessibility_hidden">Salta la descrizione di questo t&egrave; o infuso</a>
          <img src="'.$immagine.'" alt="'.$descrizione_immagine.'"/>
          <h4>Ingredienti</h4>
          <p>'.$ingredienti.'</p>
          <h4>Descrizione</h4>
          <p>'.$descrizione.'</p>
          <h4>Preparazione</h4>
          <p>'.$preparazione.'</p>
           '.($logged_admin
       ? '<a href="form_teinfusi.php?id='.$id.'">Modifica</a>
          <a href="deleteTeInfusi.php?id='.$id.'">Rimuovi</a>'
       : '').'
          <span class="expand_btn material-icons-round">expand_more</span>
        </div>
        ';
    }

    $contenuto = file_get_contents('teinfusi.html');
    $contenuto = ($logged_admin
                 ? str_replace("%NEW_TE_O_INFUSI%", '<a href="form_teinfusi.php">Aggiungi un nuovo T&egrave; o Infuso</a>', $contenuto)
                 : str_replace("%NEW_TE_O_INFUSI%", '', $contenuto));
    $contenuto = str_replace("%LISTA_TE_E_INFUSI%", $lista, $contenuto);
    $pagina = Genera_pagina::genera("website/www/html/base.html", "teinfusi", $contenuto);
    echo $pagina;
  }
  else {
    header('Location: website/www/php/front/redirect.php?error=1');
    exit;
  }
?>

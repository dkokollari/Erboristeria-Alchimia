<?php
  header('Content-Type: text/html; charset=UTF-8');

  require_once("DBAccess.php");
  require_once("Image.php");
  require_once('menu_pagina.php');

  $con = new DBAccess();
  if($con->openConnection()) {
    $pagina = file_get_contents('base.html');
    $pagina = str_replace("%TITOLO_PAGINA%", "T&egrave; &amp; Infusi", $pagina);
    $pagina = str_replace("%DESCRIZIONE_PAGINA%", "Visualizza i nostri te e infusi", $pagina);
    $pagina = str_replace("%KEYWORDS_PAGINA%", "te, infusi, te e infusi, erboristeria, alchimia", $pagina);
    $pagina = str_replace("%CONTAINER_PAGINA%", "container_te_e_infusi", $pagina);
    $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("teinfusi.php"), $pagina);

    $lista_te_e_infusi = $con->getTeInfusi();

    foreach ($lista_te_e_infusi as $row) {
      $id = $row["id_te_e_infusi"];
      $immagine = Image::getImage("./img/te_e_infusi/", $id);
      $descrizione_immagine = htmlentities($row["descrizione_immagine_te_e_infusi"]);
      $nome = htmlentities($row["nome_te_e_infusi"]);
      $ingredienti = DBAccess::nl2p(htmlentities($row["ingredienti_te_e_infusi"]));
      $descrizione = DBAccess::nl2p(htmlentities($row["descrizione_te_e_infusi"]));
      $preparazione = DBAccess::nl2p(htmlentities($row["preparazione_te_e_infusi"]));

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
           '.($_SESSION['auth'] ?
         '<a href="updateTeInfusi.php?id='.$id.'">Modifica</a>
          <a href="deleteTeInfusi.php?id='.$id.'">Rimuovi</a>'
          : "").'
          <span class="expand_btn material-icons-round">expand_more</span>
        </div>

        ';
    }

    $container = file_get_contents('teinfusi.html');
    $containter = ($_SESSION['auth']
                  ? str_replace("%NEW_TE_O_INFUSI%", '<a href="inserimento_teinfusi.php">Aggiungi un nuovo T&egrave; o Infuso</a>', $container)
                  : str_replace("%NEW_TE_O_INFUSI%", '', $container));
    $container = str_replace("%LISTA_TE_E_INFUSI%", $lista, $container);
    $pagina = str_replace("%CONTENUTO_PAGINA%", $container, $pagina);
    echo $pagina;
  }
  else {
    header('Location: redirect.php?error=1');
    exit;
  }
?>

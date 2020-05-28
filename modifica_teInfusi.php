<?php
  header('Content-Type: text/html; charset=UTF-8');

  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  if($con->openConnection()){
    if (!$con->connection->set_charset("utf8")){
      //printf("Error loading character set utf8: %s\n", $con->error);
      exit;
    }

    $pagina = file_get_contents('modifica_teInfusi.html');
    $lista_te_e_infusi = $con->getTeInfusi();

    foreach ($lista_te_e_infusi as $row){
      $id = $row["id_te_e_infusi"];
      $immagine = Image::getImage("./img/te_e_infusi/", $row["id_te_e_infusi"]);
      $descrizione_immagine = htmlentities($row["descrizione_immagine_te_e_infusi"]);
      $nome = htmlentities($row["nome_te_e_infusi"]);
      $ingredienti = nl2p(htmlentities($row["ingredienti_te_e_infusi"]));
      $descrizione = nl2p(htmlentities($row["descrizione_te_e_infusi"]));
      $preparazione = nl2p(htmlentities($row["preparazione_te_e_infusi"]));
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
        <a href="updateTeInfusi.php?id='.$id.'">Modifica</a>
        <span class="expand_btn material-icons-round">expand_more</span>
        </div>

        ';
    }

    $pagina = str_replace("%LISTA_TE_E_INFUSI%", $lista, $pagina);
    echo $pagina;
  }
  else{
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit;
  }

  /* mette i tag di paragrafo ad ogni nuova riga */
  function nl2p($text){
    return str_replace(array("\r\n", "\r", "\n"), "</p><p>", $text);
  }
?>

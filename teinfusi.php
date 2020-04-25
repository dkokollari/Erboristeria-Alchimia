<?php
  header('Content-Type: text/html; charset=ISO-8859-1');

  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  if($con->openConnection()){
    if (!$con->connection->set_charset("utf8")){
      //printf("Error loading character set utf8: %s\n", $con->error);
      exit;
    }

    $pagina = file_get_contents('teinfusi.html');
    $lista_te_e_infusi = $con->getTeInfusi();
    $image = new Image();

    foreach ($lista_te_e_infusi as $row){
      $lista .=
      '<button class="card collapsed">
        <img src="'.$image->getImage("./img/te_e_infusi/", $row["id_te_e_infusi"]).'"/>
        <h3><dt>'.htmlentities($row["nome_te_e_infusi"]).'</dt></h3>
        <dd>
          <h4>Ingredienti</h4>
          <p>'.nl2p(htmlentities($row["ingredienti_te_e_infusi"])).'</p>
          <h4>Descrizione</h4>
          <p>'.nl2p(htmlentities($row["descrizione_te_e_infusi"])).'</p>
          <h4>Preparazione</h4>
          <p>'.nl2p(htmlentities($row["preparazione_te_e_infusi"])).'</p>
        </dd>
        <i class="material-icons-round">expand_more</i>
      </button>

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

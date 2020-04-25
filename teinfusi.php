<?php
  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  if($con->openConnection()){
    $pagina = file_get_contents('teinfusi.html');
    $lista_te_e_infusi = $con->getTeInfusi();

    foreach ($lista_te_e_infusi as $row){
      $lista .=
      '<button class="card collapsed">
        <img src="img/te_e_infusi/'.(file_exists("img/te_e_infusi/".$row["id_te_e_infusi"].".jpg") ? $row["id_te_e_infusi"].".jpg" : "0.jpg").'"/>
        <h3><dt>'.$row["nome_te_e_infusi"].'</dt></h3>
        <dd>
          <h4>Ingredienti</h4>
          <p>'.nl2p($row["ingredienti_te_e_infusi"]).'</p>
          <h4>Descrizione</h4>
          <p>'.nl2p($row["descrizione_te_e_infusi"]).'</p>
          <h4>Preparazione</h4>
          <p>'.nl2p($row["preparazione_te_e_infusi"]).'</p>
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

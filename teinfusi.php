<?php
  require_once("DBAccess.php");

  /* mette i tag di paragrafo ad ogni nuova riga */
  function nl2p($text){
    return str_replace(array("\r\n", "\r", "\n"), "</p><p>", $text);
  }

  $con = new DBAccess();
  if($con->openConnection()){
    $pagina = file_get_contents('teinfusi.html');
    $lista = "";

    $pagina = str_replace("%LISTA_TE_E_INFUSI%", $lista, $pagina);

    echo $pagina;
  }
  else{
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit;
  }

  $query_visualizza_te_e_infusi = "SELECT id_te_e_infusi, nome_te_e_infusi, ingredienti_te_e_infusi, descrizione_te_e_infusi, preparazione_te_e_infusi FROM te_e_infusi";

  if($result = mysqli_query($con, $query_visualizza_te_e_infusi)){
    while($row = mysqli_fetch_assoc($result)){
      print(
        '<div class="card collapsed">'."\n".
        //controllo se l'immagine esiste (importante che sia in formato jpg) e in caso la visualizzo, altrimenti mostro un immagine statica presente nella directory
        ' <img src="img/te_e_infusi/'.(file_exists("img/te_e_infusi/".$row["id_te_e_infusi"].".jpg") ? $row["id_te_e_infusi"].'.jpg' : '0.jpg').'"/>'."\n".
        ' <h3>'.htmlentities($row["nome_te_e_infusi"], ENT_NOQUOTES).'</h3>'."\n".
        '   <h4>Ingredienti</h4>'."\n".
        '   <p>'.nl2p(htmlentities($row["ingredienti_te_e_infusi"], ENT_NOQUOTES)).'</p>'."\n".
        '   <h4>Descrizione</h4>'."\n".
        '   <p>'.nl2p(htmlentities($row["descrizione_te_e_infusi"], ENT_NOQUOTES)).'</p>'."\n".
        '   <h4>Preparazione</h4>'."\n".
        '   <p>'.nl2p(htmlentities($row["preparazione_te_e_infusi"], ENT_NOQUOTES)).'</p>'."\n".
        ' <i class="material-icons-round">expand_more</i>'."\n".
        '</div>'."\n"
      );
    }
    mysqli_free_result($result);
  }

?>

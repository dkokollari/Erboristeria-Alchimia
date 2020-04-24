<?php
  require_once("DBAccess.php");

  /* mette i tag di paragrafo ad ogni nuova riga */
  function nl2p($text){
    return str_replace(array("\r\n", "\r", "\n"), "</p><p>", $text);
  }

  $con = new DBAccess();
  if($con->openConnection()){
    $pagina = file_get_contents('teinfusi.html');
    $lista = $con->getTeInfusi();

    $pagina = str_replace("%LISTA_TE_E_INFUSI%", $lista, $pagina);
    echo $pagina;
  }
  else{
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit;
  }

?>

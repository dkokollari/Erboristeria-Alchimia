<?php
  require_once("DBAccess.php");

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

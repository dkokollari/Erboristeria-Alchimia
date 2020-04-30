<?php
require_once("DBAccess.php");
require_once("Image.php");

$pagina = file_get_contents('modifica_teInfusi.html');
$con = new DBAccess();

if($con->openConnection()){
  $teInfusi = $con->getTeInfusi();
  if($teInfusi != null){
  	$listaTeInfusi = "";
    foreach($teInfusi as $obj){
    	$listaTeInfusi .= '<div class="card collapsed">'."\n".
                //controllo se l'immagine esiste (importante che sia in formato jpg) e in caso la visualizzo, altrimenti mostro un immagine statica presente nella directory
                ' <img src="img/te_e_infusi/'.(file_exists("img/te_e_infusi/".$obj['Id'].".jpg") ? $obj['Id'].'.jpg' : '0.jpg').'"/>'.
                '<h3>'.htmlentities($obj['Nome'], ENT_NOQUOTES).'</h3>'.
                '<h4>Ingredienti</h4>'.
                '<p>'.htmlentities($obj['Ingredienti'], ENT_NOQUOTES).'</p>'.
                '<h4>Descrizione</h4>'.
                '<p>'.htmlentities($obj['Descrizione'], ENT_NOQUOTES).'</p>'.
                '<h4>Preparazione</h4>'.
                '<p>'.htmlentities($obj['Preparazione'], ENT_NOQUOTES).'</p>'.
                '<a href="updateTeInfusi.php?id='.$obj['Id'].'">Modifica</a>'.
                '<a href="deleteTeInfusi.php?id='.$obj['Id'].'">Delete</a>'.
                '<i class="material-icons-round">expand_more</i>'.
                '</div>'."\n";
    }
    $pagina = str_replace("<teinfusi/>", $listaTeInfusi, $pagina);
  }

}
else{
  echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
  exit;
}

echo $pagina;

?>

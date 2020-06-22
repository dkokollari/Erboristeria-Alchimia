<?php
  require_once("session.php");
  require_once("DBAccess.php");

  $pagina = file_get_contents("../html/ricerca.html");

  if(isset($_POST['search'])) {
    $con = new DBAccess();
    if(!$con->openConnection()) {
      header('Location: redirect.php?error=1');
      exit;
    }
    $noOption = "none"; // costante: valore opzione "Seleziona filtro"
    $search_value = trim($_POST['search']);
    $sex_filter = mysql_real_escape_string(trim($_POST['sesso']));
    $categ_filter = mysql_real_escape_string(trim($_POST['categoria']));
    $casa_prod_filter =  mysql_real_escape_string(trim($_POST['casa_prod']));
    $resultsToPrint = "";
    $query_ricerca = 'SELECT `nome_articolo`,
                             `quantita_magazzino_articolo`
                        FROM `articoli`
                             %categorie%
                             %ditte%
                             %produzioni%
                       WHERE `nome_articolo` LIKE ?
                             %sex%
                             %categ%
                             %casa_prod%
                    ORDER BY `id_articolo` DESC';

    if($sex_filter != $noOption)
      $query_ricerca = str_replace("%sex%", "AND sesso_target = '$sex_filter'", $query_ricerca);
    else
      $query_ricerca = str_replace("%sex%", "" , $query_ricerca);

    if($categ_filter != $noOption) {
        $query_ricerca = str_replace("%categorie%", ",categorie" , $query_ricerca);
        $query_ricerca = str_replace("%categ%",
                                     "AND categoria_articolo = id_categoria
                                      AND nome_categoria = '$categ_filter'", $query_ricerca);
    }
    else {
      $query_ricerca = str_replace("%categorie%", "" , $query_ricerca);
      $query_ricerca = str_replace("%categ%", "" , $query_ricerca);
    }

    if($casa_prod_filter != $noOption) {
      $query_ricerca = str_replace("%ditte%", ",ditte" , $query_ricerca);
      $query_ricerca = str_replace("%produzioni%", ",produzioni" , $query_ricerca);
      $query_ricerca = str_replace("%casa_prod%", "AND articolo_produzione = id_articolo
                                                   AND ditta_produzione = id_ditta
                                                   AND nome_ditta = '$casa_prod_filter' ", $query_ricerca);
    }
    else {
       $query_ricerca = str_replace("%ditte%", "" , $query_ricerca);
       $query_ricerca = str_replace("%produzioni%", "" , $query_ricerca);
       $query_ricerca = str_replace("%casa_prod%", "" , $query_ricerca);
    }

    if(!$stmt = mysqli_prepare($con->connection, $query_ricerca)) {
      die('prepare() failed: ' . htmlspecialchars(mysqli_error($con->connection)));
    }

    $search_value = preg_replace("#[^0-9a-z]#", "", $search_value); // capire cosa fa!!!
    $search_value = '%'.$search_value.'%';
    $stmt->bind_param("s", $search_value);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0)
      $resultsToPrint = '<p>La ricerca non ha prodotto alcun risultato</p>';
    else {
      $resultsToPrint = '<ul id="myUL">';
    	while($row = $result->fetch_assoc()) {
      	$resultsToPrint .= '<li>' .'nome: ' .$row['nome_articolo']. ' quantit&agrave;: ' .$row['quantita_magazzino_articolo']. '</li>';
    	}
      $resultsToPrint .= '</ul>';
    }

    $stmt->close();
    $con->closeConnection();
    $pagina = str_replace("%SEARCH_RESULTS%", $resultsToPrint , $pagina);
    echo $pagina;
  }
  else {
    $pagina = str_replace("%SEARCH_RESULTS%", "" , $pagina);
    $pagina = str_replace("%SEARCH_RESULTS%", "" , $pagina);
    echo $pagina;
  }
?>

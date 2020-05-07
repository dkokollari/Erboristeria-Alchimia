<?php
require_once("DBAccess.php");

$pagina = file_get_contents('ricerca.html');

if(isset($_POST['search'])) {
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }
  //Stabilita connessione al db
  /* visualizza il set di caratteri utilizzato dal client */
  //printf("Initial character set: %s\n", $con->character_set_name());
  /* cambia il set di caratteri in utf8 */
  if (!$conn->connection->set_charset("utf8")) {
      //printf("Error loading character set utf8: %s\n", $con->error);
      exit(1);
  }

  $search_value = trim($_POST['search']);
  $sex_values = array(
              "per_lui" => 0,
              "per_lei" => 1,
              "unisex"  => 2);
  $sex_filter = $sex_values[' . 'trim($_POST['sesso'])' .'];
  $categ_filter = trim($_POST['categoria']);
  $casa_prod_filter = trim($_POST['casa_prod']);
  $resultsToPrint = "";
  $query_ricerca = "SELECT nome_articolo, quantita_magazzino_articolo
                    FROM articoli %categorie% %ditte% %produzioni%
                    WHERE nome_articolo LIKE ? %sex% %categ% %casa_prod%
                    ORDER BY id_articolo DESC";

  if(isset($_POST['sesso'])) {
    $query_ricerca = str_replace("%sex%", "AND sesso_target = " . $sex_filter , $query_ricerca);
  }
  else {
    $query_ricerca = str_replace("%sex%", "" , $query_ricerca);
  }

  if(isset($_POST['categoria'])) {
      $query_ricerca = str_replace("%categorie%", ",categorie" , $query_ricerca);
      $query_ricerca = str_replace("%categ%", "AND categoria_articolo = id_categoria
        AND categoria_articolo = " . $categ_filter , $query_ricerca);
  }
  else {
    $query_ricerca = str_replace("%categorie%", "" , $query_ricerca);
    $query_ricerca = str_replace("%categ%", "" , $query_ricerca);
  }

  if(isset($_POST['casa_prod'])) {
    $query_ricerca = str_replace("%ditte%", ",ditte" , $query_ricerca);
    $query_ricerca = str_replace("%produzioni%", ",produzioni" , $query_ricerca);
    $query_ricerca = str_replace("%casa_prod%", "AND articolo_produzione = id_articolo
      AND ditta_produzione = id_ditta AND nome_ditta = " . $casa_prod_filter , $query_ricerca);
  }
  else {
     $query_ricerca = str_replace("%ditte%", "" , $query_ricerca);
     $query_ricerca = str_replace("%produzioni%", "" , $query_ricerca);
     $query_ricerca = str_replace("%casa_prod%", "" , $query_ricerca);
  }

  if(!$stmt = mysqli_prepare($conn->connection, $query_ricerca)) {
    die('prepare() failed: ' . htmlspecialchars(mysqli_error($conn->connection)));
  }

  $search_value = preg_replace("#[^0-9a-z]#", "", $search_value); //capire cosa fa!!!
  $search_value = '%'.$search_value.'%';
  $stmt->bind_param("s", $search_value);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows === 0) {
    $resultsToPrint = '<p>La ricerca non ha prodotto alcun risultato</p>';
  }
  else while($row = $result->fetch_assoc()) {
    $resultsToPrint .= '<li>' .'nome: ' .$row['nome_articolo']. 'quantit&agrave;: ' .$row['nome_articolo']. '</li>';
  }

  $stmt->close();
  $pagina = str_replace("%SEARCH_RESULTS%", $resultsToPrint , $pagina);
  echo $pagina;
  $conn->closeConnection();
}
else {
  $pagina = str_replace("%SEARCH_RESULTS%", "" , $pagina);
  $pagina = str_replace("%SEARCH_RESULTS%", "" , $pagina);
  echo $pagina;
}
?>

<?php
require_once("DBAccess.php");

$pagina = file_get_contents('ricerca.html');

if(isset($_POST['search'])) {
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<div class= "errori">' . "Impossibile connettersi al database riprovare pi&ugrave; tardi" . '</div>';
   exit(1);
  }
  //Stabilita connessione al db
  /* visualizza il set di caratteri utilizzato dal client */
  //printf("Initial character set: %s\n", $con->character_set_name());
  /* cambia il set di caratteri in utf8 */
  if (!$conn->set_charset("utf8")) {
      //printf("Error loading character set utf8: %s\n", $con->error);
      exit(1);
  }

  $search_value = trim($_POST['search']);
  $resultsToPrint = "";

  $query_ricerca = "SELECT nome_articolo, quantita_magazzino_articolo
                    FROM articoli
                    ORDER BY id_articolo DESC
                    WHERE nome_articolo = ?";

  if(!$stmt = mysqli_prepare($conn->connection, $query)) {
    die('prepare() failed: ' . htmlspecialchars(mysqli_error($conn->$connection)));
  }

  $stmt->bind_param("s", $search_value);
  $stmt->execute();
  $result = $stmt->get_result();

  if($result->num_rows === 0) {
    $resultsToPrint = '<span>La ricerca non ha prodotto alcun risultato</span>';
  }
  else while($row = $result->fetch_assoc()) {
    $resultsToPrint .= '<li>' .'nome: '. .$row['nome_articolo']. 'quantit&agrave;: ' .$row['nome_articolo']. '</li>';
  }
  $stmt->close();
  $pagina = str_replace("%SEARCH_RESULTS%", $logged , $resultsToPrint);
  echo $pagina;
  $conn->closeConnection();
}
?>

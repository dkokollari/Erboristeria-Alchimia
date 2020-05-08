<?php
  require_once("DBAccess.php");
  $pagina = file_get_contents('cart.html');
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }
  
  $productToPrint;
  $query = "SELECT * FROM articoli ORDER by id_articolo ASC";
  $result = mysqli_query($conn->connection, $query);
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      //print_r($row);
      $productToPrint .= '<div class = "col-sn-4 col-md-3">' . "\n" .
      '<form method="post" action="cart.php?action=add&id='. $row["id_articolo"] .  '">'. "\n" .
      '<div class="products">' . "\n" .
      '<img src="img/articoli/'.(file_exists("
      img/articoli/".$row["id_articolo"].".jpg") ? $row["id_articolo"].'.jpg' : '0.jpg').'" class="img-responsive"/>'."\n" .
      '<h4 class="text-info">' . $row["nome_articolo"] . '</h4>' . "\n" .
      '<h4>' . $row["prezzo_articolo"] . ' €' . '</h4>' ."\n" .
      '<input type="text" name="quantity" class="form-control" value="1" />' ."\n" .
      '<input type="submit" name="add_to_cart" class="btn btn-info CUSTOM_MARGIN" value="Add to Cart" />' . "\n" .
      '</div>' . "\n" . '</form>' . "\n" . '</div>' ."\n";
    }
  }
  $conn->closeConnection();
  $pagina = str_replace("%PRODUCTS%", $productToPrint, $pagina);
  echo $pagina;
?>

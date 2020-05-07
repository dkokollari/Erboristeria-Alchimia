<?php
  require_once("DBAccess.php");
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }
  $query = "SELECT * FROM articoli ORDER by id ASC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      print_r($row)
    }
  }
?>

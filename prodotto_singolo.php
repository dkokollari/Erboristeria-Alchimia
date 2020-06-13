<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("Image.php");

  if(isset($_GET['id_articolo']) && is_numeric($_GET['id_articolo'])) {
    $id_articolo = (int)($_GET['page']) > 0 ? (int)$_GET['page'] : 1;
    $conn = new DBAccess();
    if(!$conn->openConnection()) {
     echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
     exit(1);
    }

    $pagina = file_get_contents('prodotto_singolo.html');
    $row = '';
    $iscriviti = '<p>Per poter acquistare online il seguente prodotto, se sei già registrato sul sito, fai il <a href="login.php">login</a>;
    altrimenti, che aspetti? <a href="register.php">Registrati</a> sul nostro sito: i clienti pi&ugrave; fedeli hanno una carta fedeltà che viene
    aggiornata ad ogni acquisto: quando la tua carta è piena, corri in negozio: avrai diritto ad uno sconto del 25&#37; su un prodotto
    a scelta!</p>';

    $query = "SELECT nome_articolo, prezzo_articolo, sesso_target, nome_ditta, nome_linea, nome_categoria
    FROM articoli, ditte, produzioni, linee, categorie
    WHERE  id_articolo = '" . $id_articolo . "' AND articolo_produzione = id_articolo AND ditta_produzione = id_ditta
    AND id_linea = linea_articolo AND categoria_articolo = id_categoria";
    $result = mysqli_query($conn->connection, $query);
    if($result) { /*brutto if .... non ce l'else!!!!!!!!!!!!!!*/
      $row = $result->fetch_assoc();
    }/* else {
    printf("Error: %s\n", mysqli_error($conn->connection));
    break;
    }*/
    $meta_description = '<meta name="description" content="' . $row['nome_articolo'] .'"/>';
    $meta_keywords = '<meta name="keywords" content="' . str_replace('', ', ', $row['nome_articolo']) . ' alt=' . $row['nome_articolo']  . '"/>';
    $img_1 = '<img id="immagine_prodotto" src="' . Image::getImage("../img/products/", $row['id_articolo']) . ' alt=' . $row['nome_articolo']  .  '"/>';
    $img_2 = '<img id="topbar_image" src="' . Image::getImage("../img/products/", $row['id_articolo']) . '"/>';
    $nome = $row['nome_articolo'];
    $prezzo = $row['prezzo_articolo'];
    $linea = $row['linea_articolo'];
    $categoria = $row['nome_categoria'];
    $desc_breve = '<p>' . $row['descrizione_articolo'] . '</p>'; //// WARNING: cè bisogno di un attributo 'descrizione breve' a db!
    $desc_completa = '<p>' . $row['descrizione_articolo'] . '</p>';
    $preparazione =  '<p>' . $row['descrizione_articolo'] . '</p>'; /// WARNING: cè bisogno di un attributo 'preparazione' a db!
    $ingredienti =  '<p>' . $row['descrizione_articolo'] . '</p>'; /// WARNING: cè bisogno di un attributo 'preparazione' a db!

    if($_SESSION['utente'] == "Visitatore") {
      $pagina = str_replace('<a class="classic_btn">aggiungi al carrello</a>' , $iscriviti , $pagina);
    }
    $pagina = str_replace('%META_DESCRIPTION%' , $meta_description , $pagina);
    $pagina = str_replace('%META_KEYWORDS%' , $meta_keywords , $pagina);
    $pagina = str_replace('%PRODUCT_IMG_1%' , $img_1 , $pagina);
    $pagina = str_replace('%PRODUCT_IMG_2%' , $img_2 , $pagina);
    $pagina = str_replace('%META_DESCRIPTION%' , $nome , $pagina);
    $pagina = str_replace('%META_KEYWORDS%' , $prezzo , $pagina);
    $pagina = str_replace('%PRODUCT_IMG_1%' , $linea , $pagina);
    $pagina = str_replace('%PRODUCT_IMG_2%' , $categoria , $pagina);
    $pagina = str_replace('%SHORT_DESCRIPTION%' , $desc_breve , $pagina);
    $pagina = str_replace('%LONG_DESCRIPTION%' , $desc_completa , $pagina);
    $pagina = str_replace('%HOW_TO%' , $preparazione , $pagina);
    $pagina = str_replace('%INGREDIENTS%' , $ingredienti , $pagina);
    echo $pagina;
}
?>

<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("Image.php");

  if(isset($_GET['id_articolo']) && is_numeric($_GET['id_articolo'])) {
    $id_articolo = (int)($_GET['id_articolo']) > 0 ? (int)$_GET['id_articolo'] : 1;
    $conn = new DBAccess();
    if(!$conn->openConnection()) {
      header('Location: redirect.php?error=1');
      exit;
    }

    $pagina = file_get_contents("../html/prodotto_singolo.html");
    $row = '';
    $iscriviti = '<p>Per poter acquistare online il seguente prodotto, se sei già registrato sul sito, fai il <a href="login.php">login</a>;
    altrimenti, che aspetti? <a href="register.php">Registrati</a> sul nostro sito: i clienti pi&ugrave; fedeli hanno una carta fedeltà che viene
    aggiornata ad ogni acquisto: quando la tua carta è piena, corri in negozio: avrai diritto ad uno sconto del 25&#37; su un prodotto
    a scelta!</p>';

    $query = "SELECT nome_articolo, prezzo_articolo, sesso_target, descrizione_articolo, nome_ditta, nome_linea, nome_categoria
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
    $meta_keywords = '<meta name="keywords" content="' . str_replace('', ', ', $row['nome_articolo']) . '"/>';
    $img_1 = '<img id="immagine_prodotto" src="' . Image::getImage("../img/products/small_img/", $row['id_articolo']) . '" alt="' . $row['nome_articolo']  .  '"/>';
    $img_2 = '<img id="topbar_image" src="' . Image::getImage("../img/products/small_img/", $row['id_articolo']) .  '" alt="' . $row['nome_articolo']  . '"/>';
    $nome = $row['nome_articolo'];
    $prezzo = $row['prezzo_articolo'] . '&euro;';
    $linea = $row['nome_linea'];
    $categoria = $row['nome_categoria'];
    $desc_breve = $row['descrizione_articolo']; //// WARNING: cè bisogno di un attributo 'descrizione breve' a db!
    $desc_completa = $row['descrizione_articolo'];
    $preparazione =  $row['descrizione_articolo']; /// WARNING: cè bisogno di un attributo 'preparazione' a db!
    $ingredienti =  $row['descrizione_articolo']; /// WARNING: cè bisogno di un attributo 'preparazione' a db!

    if($_SESSION['tipo_utente'] == 'Visitatore') {
      $pagina = str_replace('%ADD_TO_CART%', $iscriviti , $pagina);
    } else if($_SESSION['tipo_utente'] == "User") {
      if(!isset($_GET['addedProduct']) && !isset($_GET['qta'])) {
        $pagina = str_replace(
          '%ADD_TO_CART%',
          '<a class="classic_btn" href="aggiunta_rimozione_prodotti_carrello.php?add_to_cart&amp;id_articolo='.$id_articolo.'&amp;nome_articolo="'.urlencode($nome).'"&amp;prezzo_articolo='.$prezzo.'>aggiungi al carrello</a>',
          $pagina);
      } else {
        $pagina = str_replace('%ADD_TO_CART%' ,
        '<p class="addedProduct">Prodotto aggiunto al carrello con successo! In questo momento ce ne sono '. $_GET['qta'] .' copie nel tuo carrello.</p>' ."\n".
        '<a class="classic_btn" href="aggiunta_rimozione_prodotti_carrello.php?add_to_cart&amp;id_articolo='.$id_articolo.'&amp;nome_articolo="'.
        urlencode($nome).'"&amp;prezzo_articolo='.$prezzo.'>aggiungi al carrello</a>', $pagina);
      }
    } else if($_SESSION['tipo_utente'] == "Admin") { //// WARNING: all'admin non faccio vedere tasto aggiungi
        $pagina = str_replace('%ADD_TO_CART%' , '' , $pagina);
    }

    $conn->closeConnection();
    $pagina = str_replace('%META_DESCRIPTION%' , $meta_description , $pagina);
    $pagina = str_replace('%META_KEYWORDS%' , $meta_keywords , $pagina);
    $pagina = str_replace('%PRODUCT_IMG_1%' , $img_1 , $pagina);
    $pagina = str_replace('%PRODUCT_IMG_2%' , $img_2 , $pagina);
    $pagina = str_replace('%NAME%' , $nome , $pagina);
    $pagina = str_replace('%PRICE%' , $prezzo , $pagina);
    $pagina = str_replace('%LINE%' , $linea , $pagina);
    $pagina = str_replace('%CATEGORY%' , $categoria , $pagina);
    $pagina = str_replace('%SHORT_DESCRIPTION%' , $desc_breve , $pagina);
    $pagina = str_replace('%LONG_DESCRIPTION%' , $desc_completa , $pagina);
    $pagina = str_replace('%HOW_TO%' , $preparazione , $pagina);
    $pagina = str_replace('%INGREDIENTS%' , $ingredienti , $pagina);
    echo $pagina;
  }
  else {
    header('Location : index.php');
  }
?>

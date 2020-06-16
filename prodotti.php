<?php
  require_once("DBAccess.php");
  require_once("Image.php");
  $pagina = file_get_contents('prodotti.html');
  $conn = new DBAccess();
  if(!$conn->openConnection()) {
   echo '<p class= "errori">' . "Impossibile connettersi al database: riprovare pi&ugrave; tardi" . '</p>';
   exit(1);
  }

  //Stabilita connessione al db


  /*-------------menu a tendina sesso---------------*/
  $opt_sesso = '<label for="sesso">Sesso</label>' . "\n" .
                '<select name="sesso" id="sesso">' . "\n" .
                '<option selected value="none">Seleziona filtro</option>' . "\n" .
                '<option value="Unisex">Unisex</option>' . "\n" .
                '<option value="Per lei">Per lei</option>' . "\n" .
                '<option value="Per lui">Per lui</option>' . "\n" .'</select>' . "\n";

  /*-------------menu a tendina categoria---------------*/
  $opt_categoria = '<label for="categoria">Categoria</label>' . "\n" .
                   '<select name="categoria" id="categoria">' . "\n" .
                   '<option selected value="none">Seleziona filtro</option>' . "\n";
  $result = $conn->connection->query('SELECT distinct nome_categoria from categorie');
  while($row = $result->fetch_assoc()) {
    $opt_categoria .= '<option value="' . $row['nome_categoria'] . '">' .$row['nome_categoria'] . '</option>' . "\n";
  }
  $opt_categoria .= '</select>' . "\n";

  /*-------------menu a tendina casa produttrice---------------*/
  $opt_casa_prod = '<label for="casa_prod">Casa produttrice</label>' . "\n" .
            '<select name="casa_prod" id="casa_prod">' . "\n" .
            '<option selected value="none">Seleziona filtro</option>' . "\n";
  $result = $conn->connection->query('SELECT distinct nome_ditta from ditte');
  while($row = $result->fetch_assoc()) {
    $opt_casa_prod .= '<option value="' . $row['nome_ditta'] . '">' . $row['nome_ditta'] . '</option>' . "\n";
  }
  $opt_casa_prod .= '</select>' . "\n";

  if(isset($_GET['search'])) {
    $noOption = "none"; //indica l'opzione "Seleziona Filtro" per i menu a tendina
    $search_value = mysql_real_escape_string(trim($_GET['search']));

    $sex_filter = mysql_real_escape_string(trim($_GET['sesso']));
    $categ_filter = mysql_real_escape_string(trim($_GET['categoria']));
    $casa_prod_filter =  mysql_real_escape_string(trim($_GET['casa_prod']));

    $results_per_page = 6;
    //controllo che il valore di page passato in querystring sia intero > 0, altrimenti lo setto a 1
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && (int)($_GET['page']) > 0 ? (int)$_GET['page'] : 1;
    $start = ($page > 1) ? ($page*$results_per_page) - $results_per_page : 0;

    $query_ricerca = 'SELECT SQL_CALC_FOUND_ROWS *
                    FROM articoli %categorie% %ditte% %produzioni%
                    WHERE nome_articolo LIKE ? %sex% %categ% %casa_prod%
                    ORDER BY id_articolo DESC LIMIT ' . $start . ', ' . $results_per_page;


    //Entro in questo if sse il filtro in querystring != "none" && è un valore selezionabile, altrimenti
    //ignoro l'eventuale valore non conosciuto presente in querystring e lascio il filtro settato a "none" (= "Seleziona filtro")
    if($sex_filter != $noOption && strpos($opt_sesso,
    '<option value="' . $sex_filter . '">' . $sex_filter . '</option>') != false) {
      //restituisco i dati usati da utente per compilare il form + aggiorno la query di ricerca
      $opt_sesso = str_replace('selected', '' , $opt_sesso);
      $opt_sesso = str_replace('<option value="' . $sex_filter . '">' . $sex_filter . '</option>',
       '<option selected value="' . $sex_filter . '">' . $sex_filter . '</option>' , $opt_sesso);
      $query_ricerca = str_replace("%sex%", "AND sesso_target = '$sex_filter'", $query_ricerca);
    }
    else {
      $query_ricerca = str_replace("%sex%", "" , $query_ricerca);
    }

    //Entro in questo if sse il filtro in querystring != "none" && è un valore selezionabile, altrimenti
    //ignoro l'eventuale valore non conosciuto presente in querystring e lascio il filtro settato a "none" (= "Seleziona filtro")
    if($categ_filter != $noOption && strpos($opt_categoria,
    '<option value="' . $categ_filter . '">' . $categ_filter . '</option>') != false) {
      $opt_categoria = str_replace('selected', '' , $opt_categoria);
      $opt_categoria = str_replace('<option value="' . $categ_filter . '">' . $categ_filter . '</option>',
       '<option selected value="' . $categ_filter . '">' . $categ_filter . '</option>' , $opt_categoria);
      $query_ricerca = str_replace("%categorie%", ",categorie" , $query_ricerca);
      $query_ricerca = str_replace("%categ%", "AND categoria_articolo = id_categoria
          AND nome_categoria = '$categ_filter'", $query_ricerca);
    }
    else {
      $query_ricerca = str_replace("%categorie%", "" , $query_ricerca);
      $query_ricerca = str_replace("%categ%", "" , $query_ricerca);
    }

    //Entro in questo if sse il filtro in querystring != "none" && è un valore selezionabile, altrimenti
    //ignoro l'eventuale valore non conosciuto presente in querystring e lascio il filtro settato a "none" (= "Seleziona filtro")
    //N.B: uso trim($_GET['casa_prod'] al posto di $casa_prod_filter perchè quest'ultima e' soggetta a mysql_real_escape_string,
    //quindi ha dei backslash in corrsipondenza di caratteri speciali (es, \' in L'Erbolario). Chiaro che come parametro alle
    //query du ricerca nel codice passo invece $casa_prod_filter.
    if($casa_prod_filter != $noOption && strpos($opt_casa_prod,
    '<option value="' . trim($_GET['casa_prod']) . '">' . trim($_GET['casa_prod']) . '</option>') !== false) {
      $opt_casa_prod = str_replace('selected', '' , $opt_casa_prod);
      $opt_casa_prod = str_replace('<option value="' . trim($_GET['casa_prod']) . '">' . trim($_GET['casa_prod']) . '</option>',
       '<option selected value="' . trim($_GET['casa_prod']) . '">' . trim($_GET['casa_prod']) . '</option>' , $opt_casa_prod);
      $query_ricerca = str_replace("%ditte%", ",ditte" , $query_ricerca);
      $query_ricerca = str_replace("%produzioni%", ",produzioni" , $query_ricerca);
      $query_ricerca = str_replace("%casa_prod%", "AND articolo_produzione = id_articolo
        AND ditta_produzione = id_ditta AND nome_ditta = '$casa_prod_filter' ", $query_ricerca);
    }
    else {
       $query_ricerca = str_replace("%ditte%", "" , $query_ricerca);
       $query_ricerca = str_replace("%produzioni%", "" , $query_ricerca);
       $query_ricerca = str_replace("%casa_prod%", "" , $query_ricerca);
    }

    if(!$stmt = mysqli_prepare($conn->connection, $query_ricerca)) {
      die('prepare() failed: ' . htmlspecialchars(mysqli_error($conn->connection)));
    }

    $search_value = preg_replace("#[^0-9a-z]#i", "", $search_value); //sostituisco tutto cio che non e' cifra o lettera con stringa vuota
    $search_value_query = '%'.$search_value.'%'; //per il LIKE nella query
    $stmt->bind_param("s", $search_value_query);
    $stmt->execute();
    $result = $stmt->get_result();

    $total_records = $conn->connection->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];

    $productToPrint = "";
    if($result->num_rows === 0) {
      $productToPrint = '<p>La ricerca non ha prodotto alcun risultato</p>';
    }
    else{
      $total_pages = ceil($total_records/$results_per_page); //se c'è una sola pagina non voglio mostrare un link circolare alla pagina stessa!
      $productToPrint .= '<ul>' . "\n";
      while($row = $result->fetch_assoc()) {
        $productToPrint .=
        '<li class="card_product product_description">' . "\n" .
          '<a href="prodotto_singolo.php?id_articolo=' .  $row['id_articolo'] . '">' . "\n" .
          '<img class="product_image" src="' .
              Image::getImage('img/products/small_img/', $row['id_articolo']) . '" alt=immagine "'. $row['nome_articolo'] . '"/>' . "\n" .
          '<h3 class="product_title">' .  $row['nome_articolo'] . '</h3>' . "\n" .
          '<ul>' . "\n" .
              '<li class="product_manufacturer">' . $row["nome_ditta"] . '</li>' . "\n" .
              '<li class="product_line">' . 'Linea ' . $row["nome_linea"] .'</li>' . "\n" .
              '<li class="product_tags ' . $row["nome_categoria"] . '">' . $row["nome_categoria"] . '</li>' . "\n" .
              '<li class="product_tags ' . $row["sesso_target"] . '">' . $row["sesso_target"] . '</li>' . "\n" .
              '<li class="product_price">' . $row["prezzo_articolo"] . ' &euro;</li>' . "\n" .
          '</ul>' . "\n" .
          '</a>' . "\n" .
        '</li>' . "\n";
      }
      $productToPrint .= '</ul>' . "\n";
    }
    $stmt->close();

    $links_to_result_pages = '';
    for($n_page=1; $n_page<=$total_pages; ++$n_page) {
      if($n_page != $page) {
        $links_to_result_pages .= '<a class="classic_btn" href="prodotti.php?page=' . $n_page . "&amp;search=$search_value&amp;sesso=$sex_filter" .
        "&amp;categoria=$categ_filter&amp;casa_prod=$casa_prod_filter" . '">' . $n_page . '</a>' . "\n";
      } else {
        $links_to_result_pages .= '<span class="classic_btn">'.$page.'</span>'; //tolgo link circolari
      }
    }

	$pagina = str_replace("%PRODUCTS%", $productToPrint, $pagina);
    /*$pagina = str_replace("%PRODUCTS%", htmlspecialchars($opt_casa_prod).((strpos($opt_casa_prod,
    '<option value="' . $_GET['casa_prod'] . '">' . $_GET['casa_prod'] . '</option>') !== false) ? "true" : "false"), $pagina);*/
    $pagina = str_replace("%PAGES_MENU%", $links_to_result_pages , $pagina);
  } else {
    $pagina = str_replace("%PRODUCTS%", '' , $pagina);
    $pagina = str_replace("%PAGES_MENU%", '' , $pagina);
  }

  $conn->closeConnection();
  $pagina = str_replace("%SEX_FILTER%", $opt_sesso , $pagina);
  $pagina = str_replace("%CATEGORY_FILTER%", $opt_categoria , $pagina);
  $pagina = str_replace("%COMPANY_FILTER%", $opt_casa_prod , $pagina);
  echo $pagina;
?>

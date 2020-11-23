<?php
require_once ("session.php");
require_once ("DBAccess.php");
require_once ("genera_pagina.php");
require_once ("Image.php");
require_once ("Utilities.php");

$con = new DBAccess();
if (!$con->openConnection())
{
  header('Location: redirect.php?error=1');
  exit;
}
/*-------------menu a tendina sesso---------------*/
$opt_sesso = '<label for="sesso">Sesso</label>' . "\n" .
             '<select name="sesso" id="sesso">' . "\n" .
             '<option selected="selected" value="none">Seleziona filtro</option>' . "\n" .
             '<option value="Unisex">Unisex</option>' . "\n" .
             '<option value="Donna">Donna</option>' . "\n" .
             '<option value="Uomo">Uomo</option>' . "\n" .
             '<option value="Bimbo">Bimbo</option>' .
             '</select>' . "\n";

/*-------------menu a tendina categoria---------------*/
$opt_categoria = '<label for="categoria">Categoria</label>' . "\n" . '<select name="categoria" id="categoria">' . "\n" . '<option selected="selected" value="none">Seleziona filtro</option>' . "\n";

$result = $con
  ->connection
  ->query('SELECT distinct nome_categoria from categorie');

while ($row = $result->fetch_assoc())
{
  $opt_categoria .= '<option value="' . $row['nome_categoria'] . '">' . $row['nome_categoria'] . '</option>' . "\n";
}
$opt_categoria .= '</select>' . "\n";

/*-------------menu a tendina casa produttrice---------------*/
$opt_casa_prod = '<label for="casa_prod">Produttore</label>' . "\n" . '<select name="casa_prod" id="casa_prod">' . "\n" . '<option selected="selected" value="none">Seleziona filtro</option>' . "\n";

$result = $con
  ->connection
  ->query('SELECT distinct nome_ditta from ditte');

while ($row = $result->fetch_assoc())
{
  $opt_casa_prod .= '<option value="' . htmlentities($row['nome_ditta']) . '">' . htmlentities($row['nome_ditta']) . '</option>' . "\n";
}
$opt_casa_prod .= '</select>' . "\n";

if (!isset($_GET['search']))
{
  header('Location: prodotti.php?search=&sesso=none&categoria=none&casa_prod=none');
  exit;
}
else
{
  $noOption = "none"; // indica l'opzione "Seleziona Filtro" per i menu a tendina
  $search_value = mysql_real_escape_string(trim($_GET['search']));

  $sex_filter = mysql_real_escape_string(trim($_GET['sesso']));
  $categ_filter = mysql_real_escape_string(trim($_GET['categoria']));
  $casa_prod_filter = mysql_real_escape_string(trim($_GET['casa_prod']));

  $results_per_page = 6;
  // controllo che il valore di page passato in querystring sia intero > 0, altrimenti lo setto a 1
  $page = Utilities::getNumericValue('page');
  $start = ($page > 1) ? ($page * $results_per_page) - $results_per_page : 0;

  $query_ricerca = 'SELECT SQL_CALC_FOUND_ROWS *
                    FROM articoli left join linee on linea_articolo = id_linea, categorie, ditte, produzioni
                    WHERE nome_articolo LIKE ?
                    AND articolo_produzione = id_articolo AND categoria_articolo = id_categoria
                    AND ditta_produzione = id_ditta %sex%  %categ% %casa_prod%
                    ORDER BY id_articolo DESC LIMIT ' . $start . ', ' . $results_per_page;

  // sse il filtro in querystring != "none" && è un valore selezionabile, altrimenti
  // ignoro l'eventuale valore non conosciuto presente in querystring e lascio il filtro settato a "none" (= "Seleziona filtro")
  if ($sex_filter != $noOption && strpos($opt_sesso, '<option value="' . $sex_filter . '">' . $sex_filter . '</option>') != false)
  {
    // restituisco i dati usati da utente per compilare il form + aggiorno la query di ricerca
    $opt_sesso = str_replace('selected', '', $opt_sesso);
    $opt_sesso = str_replace('<option value="' . $sex_filter . '">' . $sex_filter . '</option>', '<option selected="selected" value="' . $sex_filter . '">' . $sex_filter . '</option>', $opt_sesso);
    $query_ricerca = str_replace("%sex%", "AND sesso_target = '$sex_filter'", $query_ricerca);
  }
  else $query_ricerca = str_replace("%sex%", "", $query_ricerca);

  // sse il filtro in querystring != "none" && è un valore selezionabile, altrimenti
  // ignoro l'eventuale valore non conosciuto presente in querystring e lascio il filtro settato a "none" (= "Seleziona filtro")
  if ($categ_filter != $noOption && strpos($opt_categoria, '<option value="' . $categ_filter . '">' . $categ_filter . '</option>') != false)
  {
    $opt_categoria = str_replace('selected', '', $opt_categoria);
    $opt_categoria = str_replace('<option value="' . $categ_filter . '">' . $categ_filter . '</option>', '<option selected="selected" value="' . $categ_filter . '">' . $categ_filter . '</option>', $opt_categoria);
    $query_ricerca = str_replace("%categ%", "AND nome_categoria = '$categ_filter'", $query_ricerca);
  }
  else
  {
    $query_ricerca = str_replace("%categ%", "", $query_ricerca);
  }

  // sse il filtro in querystring != "none" && è un valore selezionabile, altrimenti
  // ignoro l'eventuale valore non conosciuto presente in querystring e lascio il filtro settato a "none" (= "Seleziona filtro")
  // N.B: uso trim($_GET['casa_prod'] al posto di $casa_prod_filter perchè quest'ultima e' soggetta a mysql_real_escape_string,
  // quindi ha dei backslash in corrsipondenza di caratteri speciali (es, \' in L'Erbolario).
  // Chiaro che come parametro alle query di ricerca nel codice passo invece $casa_prod_filter
  if ($casa_prod_filter != $noOption && strpos($opt_casa_prod, '<option value="' . trim($_GET['casa_prod']) . '">' . trim($_GET['casa_prod']) . '</option>') !== false)
  {
    $opt_casa_prod = str_replace('selected', '', $opt_casa_prod);
    $opt_casa_prod = str_replace('<option value="' . trim($_GET['casa_prod']) . '">' . trim($_GET['casa_prod']) . '</option>', '<option selected="selected" value="' . trim($_GET['casa_prod']) . '">' . trim($_GET['casa_prod']) . '</option>', $opt_casa_prod);
    $query_ricerca = str_replace("%casa_prod%", "AND nome_ditta = '$casa_prod_filter' ", $query_ricerca);
  }
  else
  {
    $query_ricerca = str_replace("%casa_prod%", "", $query_ricerca);
  }

  if (!$stmt = mysqli_prepare($con->connection, $query_ricerca))
  {
    die('prepare() failed: ' . htmlspecialchars(mysqli_error($con->connection)));
  }

  $search_value_query = '%' . $search_value . '%'; // per il LIKE nella query
  $stmt->bind_param("s", $search_value_query);
  $stmt->execute();
  $result = $stmt->get_result();

  $total_records = $con
    ->connection
    ->query("SELECT FOUND_ROWS() as total")
    ->fetch_assoc() ['total'];

  $productToPrint = "";
  if ($result->num_rows === 0)
  {
    $productToPrint = '<p>La ricerca non ha prodotto alcun risultato</p>';
  }
  else
  {
    $total_pages = ceil($total_records / $results_per_page); // se c'è una sola pagina non voglio mostrare un link circolare alla pagina stessa!
    $productToPrint .= '<ul class="container_prodotti">' . "\n";
    while ($row = $result->fetch_assoc())
    {
      $linea = empty($row["nome_linea"]) ? 'Nessuna linea' : $row["nome_linea"];
      $productToPrint .= '<li class="card_product product_description">' . "\n" .
       '<a href="prodotto_singolo.php?id_articolo=' . $row['id_articolo'] . '">' . "\n" .
       '<img class="product_image"
            src="' . Image::getImage('../img/products/small_img/', $row['id_articolo']) . '"
            alt="immagine ' . $row['nome_articolo'] . '"/>' . "\n" .
       '<h3 class="product_title">' . $row['nome_articolo'] . '</h3>' . "\n" .
       '<ul>' . "\n" .
            '<li class="product_manufacturer">' . $row["nome_ditta"] . '</li>' . "\n" .
            '<li class="product_line">' . $linea . '</li>' . "\n" .
            '<li class="product_tags ' . $row["nome_categoria"] . '">' . $row["nome_categoria"] . '</li>' . "\n" .
            '<li class="product_tags ' . $row["sesso_target"] . '">' . $row["sesso_target"] . '</li>' . "\n" .
            '<li class="product_price">' . $row["prezzo_articolo"] . ' &euro;</li>' . "\n" . '</ul>' . "\n" .
       '</a>' . "\n" .
      '</li>' . "\n";
    }
    $productToPrint .= '</ul>' . "\n";
  }
  if($total_records == 1) {
    $productToPrint = str_replace('<ul class="container_prodotti">', '<ul class="container_prodotti one_result">' ,$productToPrint);
  }
  $stmt->close();
  $con->closeConnection();

  $links_to_result_pages = '';
  if ($page > 1)
  {
    $links_to_result_pages = '<a href="prodotti.php?page=' . ($page - 1) . "&amp;search=$search_value&amp;sesso=$sex_filter" . "&amp;categoria=$categ_filter&amp;casa_prod=$casa_prod_filter" . '" class="classic_btn always_visible">Indietro</a>' . "\n";
  }
  for ($n_page = 1;$n_page <= $total_pages;++$n_page)
  {
    if ($n_page != $page && ($n_page > ($page + 1) || $n_page < ($page - 1)))
    { //in ogni pagina di risultati di ricerca, faccio vedere solo un altro link ad altre pagine di risultati
      $links_to_result_pages .= '<a href="prodotti.php?page=' . $n_page . "&amp;search=$search_value&amp;sesso=$sex_filter" . "&amp;categoria=$categ_filter&amp;casa_prod=$casa_prod_filter" . '" class="classic_btn hidden">' . $n_page . '</a>' . "\n";
    }
    else if ($n_page != $page)
    { //in ogni pagina di risultati di ricerca, faccio vedere solo tre link ad altre pagine di risultati
      $links_to_result_pages .= '<a href="prodotti.php?page=' . $n_page . "&amp;search=$search_value&amp;sesso=$sex_filter" . "&amp;categoria=$categ_filter&amp;casa_prod=$casa_prod_filter" . '" class="classic_btn">' . $n_page . '</a>' . "\n";
    }
    else
    {
      $links_to_result_pages .= "<span class=\"classic_btn selected\">$page</span>"; // tolgo link circolari

    }
  }
  if ($page < $total_pages)
  {
    $links_to_result_pages .= '<a href="prodotti.php?page=' . ($page + 1) . "&amp;search=$search_value&amp;sesso=$sex_filter" . "&amp;categoria=$categ_filter&amp;casa_prod=$casa_prod_filter" . '" class="classic_btn always_visible">Avanti</a>' . "\n";
  }
} // end if isset($_GET['search'])
$contenuto = file_get_contents("../html/prodotti.html");
$contenuto = str_replace("%PRODUCTS%", $productToPrint, $contenuto);
$contenuto = str_replace("%PAGES_MENU%", $links_to_result_pages, $contenuto);
$contenuto = str_replace("%SEX_FILTER%", $opt_sesso, $contenuto);
$contenuto = str_replace("%CATEGORY_FILTER%", $opt_categoria, $contenuto);
$contenuto = str_replace("%COMPANY_FILTER%", $opt_casa_prod, $contenuto);
$pagina = Genera_pagina::genera("../html/base5.html", "prodotti", $contenuto);
echo $pagina;
?>

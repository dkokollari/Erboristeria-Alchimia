<?php
require_once ("session.php");
require_once ("DBAccess.php");
require_once ("genera_pagina.php");
require_once ("Image.php");

$contenuto = file_get_contents("../html/prodotto_singolo.html");
if (isset($_GET['id_articolo']) && is_numeric($_GET['id_articolo']))
{
  $id_articolo = (int)($_GET['id_articolo']) > 0 ? (int)$_GET['id_articolo'] : 1;
  $con = new DBAccess();
  if (!$con->openConnection())
  {
    header('Location: redirect.php?error=1');
    exit;
  }

  $iscriviti = '<p>Per poter acquistare online il seguente prodotto, se sei già registrato sul sito, fai il <a href="login.php">login</a>;
    altrimenti, che aspetti? <a href="register.php">Registrati</a> sul nostro sito: i clienti pi&ugrave; fedeli hanno una carta fedeltà che viene
    aggiornata ad ogni acquisto: quando la tua carta è piena, corri in negozio: avrai diritto ad uno sconto del 25&#37; su un prodotto
    a scelta!</p>';

  $query = 'SELECT `nome_articolo`,
                     `prezzo_articolo`,
                     `sesso_target`,
                     `descrizione_breve_articolo`,
                     `nome_ditta`,
                     `nome_linea`,
                     `nome_categoria`
                FROM `articoli` left join `linee` on `linea_articolo` = `id_linea`,
                     `ditte`,
                     `produzioni`,
                     `categorie`
               WHERE `id_articolo` = "' . $id_articolo . '" AND
                     `articolo_produzione` = `id_articolo` AND
                     `ditta_produzione` = `id_ditta` AND
                     `categoria_articolo` = `id_categoria`';
  $result = mysqli_query($con->connection, $query);
  $lista_descrizione = $con->getDescrizioneSingolo_Articoli($id_articolo);
  if ($result) $row = $result->fetch_assoc();
  else {
    header('Location: redirect.php?error=4');
    exit;
  }
  $nome = htmlentities($row['nome_articolo']);
  $prezzo = $row['prezzo_articolo'] . '&euro;';
  $linea = empty($row["nome_linea"]) ? 'Nessuna linea' : htmlentities($row['nome_linea']);
  $sesso = '<span class="product_tags ' . htmlentities($row['sesso_target']) . '">' .
    htmlentities($row['sesso_target']) . '</span>';
  $categoria = htmlentities($row['nome_categoria']);
  $desc_breve = htmlentities($row['descrizione_breve_articolo']);
  $descrizione_formattata = "";
  foreach ($lista_descrizione as $row_descr)
  {
    $sottotitolo = htmlentities($row_descr['sottotitolo']);
    $paragrafo = htmlentities($row_descr['descrizione']);
    $descrizione_formattata .= '<h4>' . $sottotitolo . '</h4>' .
                               '<p>' . $paragrafo . '</p>';
  }
  if ($_SESSION['tipo_utente'] == 'Visitatore')
  {
    $contenuto = str_replace('%ADD_TO_CART%', $iscriviti, $contenuto);
  }
  else if ($_SESSION['tipo_utente'] == "User")
  {
    if (!isset($_GET['addedProduct']) && !isset($_GET['qta']))
    {
      $contenuto = str_replace('%ADD_TO_CART%', '<a class="classic_btn" href="aggiunta_rimozione_prodotti_carrello.php?add_to_cart&amp;id_articolo=' . $id_articolo . '&amp;nome_articolo="' . urlencode($nome) . '"&amp;prezzo_articolo=' . $prezzo . '>aggiungi al carrello</a>', $contenuto);
    }
    else
    {
      $contenuto = str_replace('%ADD_TO_CART%', '<p class="success">Prodotto aggiunto al carrello con successo! In questo momento ce ne sono ' . $_GET['qta'] . ' copie nel tuo carrello.</p>' . "\n" . '<a class="classic_btn" href="aggiunta_rimozione_prodotti_carrello.php?add_to_cart&amp;id_articolo=' . $id_articolo . '&amp;nome_articolo="' . urlencode($nome) . '"&amp;prezzo_articolo=' . $prezzo . '>aggiungi al carrello</a>', $contenuto);
    }
  }
  else if ($_SESSION['tipo_utente'] == "Admin")
  { //// WARNING: all'admin non faccio vedere tasto aggiungi
    $contenuto = str_replace('%ADD_TO_CART%', '', $contenuto);
  }
  $con->closeConnection();

  $contenuto = str_replace('%NAME%', $nome, $contenuto);
  $contenuto = str_replace('%PRICE%', $prezzo, $contenuto);
  $contenuto = str_replace('%LINE%', $linea, $contenuto);
  $contenuto = str_replace('%CATEGORY%', $categoria, $contenuto);
  $contenuto = str_replace('%SESSO%', $sesso, $contenuto);
  $contenuto = str_replace('%SHORT_DESCRIPTION%', $desc_breve, $contenuto);
  $contenuto = str_replace('%LONG_DESCRIPTION%', $descrizione_formattata, $contenuto);
  $pagina = Genera_pagina::genera("../html/base.html", "prodotto_singolo", $contenuto);
  echo $pagina;
} // end if isset($_GET['id_articolo']) && is_numeric($_GET['id_articolo'])
else
{
  header('Location: index.php');
}
?>

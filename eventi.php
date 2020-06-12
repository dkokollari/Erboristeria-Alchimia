<?php
  header('Content-Type: text/html; charset=UTF-8');

  require_once("DBAccess.php");
  require_once("Image.php");
  require_once('menu_pagina.php');

  $pagina = file_get_contents('base.html');
  $pagina = str_replace("%TITOLO_PAGINA%", "Eventi", $pagina);
  $pagina = str_replace("%DESCRIZIONE_PAGINA%", 'Qui troverai i prossimi eventi in programma e quelli passati organizzati dal negozio Erboristeria Alchimia', $pagina);
  $pagina = str_replace("%KEYWORDS_PAGINA%", 'eventi, erboristeria, alchimia', $pagina);
  $pagina = str_replace("%CONTAINER_PAGINA%", "container_te_e_infusi", $pagina);
  $pagina = str_replace("%LISTA_MENU%", menu_pagina::menu("eventi.php"), $pagina);

  $con = new DBAccess();
  if($con->openConnection()) {
    $contenuto = file_get_contents('eventi.html');
    $style = file_get_contents('stylesheet.css');
    $lista_eventi = $con->getEventi();
    $lista_descrizione = $con->getDescrizione_eventi();

    //necessario se il locale non è ancora impostato
    setlocale(LC_TIME, "it_IT");

    foreach ($lista_eventi as $row) {
      //strftime() visualizza la data nella lingua definita dal locale
      $data_ora = new DateTime($row["data_ora_evento"]);
      $giorno_testo = htmlentities(utf8_encode(strftime("%A", $data_ora->getTimestamp())));
      $giorno_numero = $data_ora->format("d");
      $mese = strftime("%B", $data_ora->getTimestamp());
      $ore_minuti = $data_ora->format("H:i");
      $immagine = Image::getImage("./img/eventi/", $row["id_evento"]);
      $descrizione_immagine = htmlentities($row["descrizione_immagine_evento"]);
      $titolo = htmlentities($row["titolo_evento"]);
      $descrizione_formattata="";
      foreach ($lista_descrizione as $row_descr) {
        if($row_descr["evento"]==$row["id_evento"]) {
          $sottotitolo = htmlentities($row_descr["sottotitolo"]);
          $descrizione_formattata .= '<li>'.$sottotitolo.'</li>
            ';
        }
      }
      $relatori = DBAccess::nl2p(htmlentities($row["relatore_evento"]));
      $indirizzo_mappa = htmlentities($row["indirizzo_mappe_evento"]);
      $url_mappa = $row["url_mappe_evento"];
      $descrizione_mappa = DBAccess::nl2p(htmlentities($row["descrizione_mappe_evento"]));
      $organizzazione = DBAccess::nl2p(htmlentities($row["organizzazione_evento"]));
      $posti_limitati = $row["prenotazione_posti_evento"];
    /*  $backgroundImg = "\n" . '#n' . $row['id_evento'] . '{ // WARNING: puo tornare utile: codice per mettere background-image univoche
        background-image: url(' . $immagine . ');
      }' . "\n";
      if(!strpos($style, $backgroundImg)) {
      $style .= $backgroundImg;
    }*/
      $lista .=
      '<strong>' . '<span class="data">' . $giorno_testo . ' ' . $giorno_numero . ' ' . $mese . '</span>' . '</strong>
       <div class="card eventi">
          <h3>' . $titolo . '</h3>
          <img src="'.$immagine.'" alt="'.$descrizione_immagine.'"/>
          <ul>
            '.$descrizione_formattata.'
          </ul>
          <h4 class="titoletto">Relatori</h4>
          <p>'.$relatori.'</p>
          <h4 class="titoletto">Mappa e data</h4>
          <a id="linkMappa" href="'.$url_mappa.'">'.$indirizzo_mappa.'</a>
          <p>'.$descrizione_mappa.'</p>
          <p id="dataEvento">
              '.$giorno_testo.' '.$giorno_numero.' '.$mese.' - ore '.$ore_minuti.'
          </p>
          <p id="org">
            '.$organizzazione.'
          </p>
          '.($posti_limitati ?
          '<p id="prenotazione">
            <span>I posti sono limitati, &egrave; gradita la prenotazione</span> (i contatti si trovano <a href="pagina_informazioni.html#contatti">qui</a>)
          </p>' : "").'
        </div>
      ';
    }

    /*file_put_contents('stylesheet.css', $style);*/
    $contenuto = str_replace("%LISTA_EVENTI%", $lista, $contenuto);
    $pagina = str_replace("%CONTENUTO_PAGINA%", $contenuto, $pagina);
    echo $pagina;
  }
  else {
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit;
  }
?>

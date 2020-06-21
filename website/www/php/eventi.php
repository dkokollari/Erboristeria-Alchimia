<?php
  require_once("session.php");
  require_once("DBAccess.php");
  require_once("Image.php");
  require_once("genera_pagina.php");

  $con = new DBAccess();
  if($con->openConnection()) {
    $style = file_get_contents("../css/stylesheet.css");
    $lista_eventi = $con->getEventi();
    $lista_descrizione = $con->getDescrizione_Eventi();

    //necessario se il locale non è ancora impostato
    setlocale(LC_TIME, "it_IT");

    foreach ($lista_eventi as $row) {
      $index++;
      //strftime() visualizza la data nella lingua definita dal locale
      $data_ora = new DateTime($row["data_ora_evento"]);
      $giorno_testo = htmlentities(utf8_encode(strftime("%A", $data_ora->getTimestamp())));
      $giorno_numero = $data_ora->format("d");
      $mese = strftime("%B", $data_ora->getTimestamp());
      $ore_minuti = $data_ora->format("H:i");
      $immagine = Image::getImage("../img/eventi/", $row["id_evento"]);
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
          <a id="linkMappa'.$index.'" href="'.$url_mappa.'">'.$indirizzo_mappa.'</a>
          <p>'.$descrizione_mappa.'</p>
          <p id="dataEvento'.$index.'">
              '.$giorno_testo.' '.$giorno_numero.' '.$mese.' - ore '.$ore_minuti.'
          </p>
          <p id="org'.$index.'">
            '.$organizzazione.'
          </p>
          '.($posti_limitati ?
          '<p id="prenotazione'.$index.'">
            <span>I posti sono limitati, &egrave; gradita la prenotazione</span> (i contatti si trovano <a href="../html/pagina_informazioni.html#contatti">qui</a>)
          </p>' : "").'
        </div>
      ';
    }

    /*file_put_contents("../css/stylesheet.css", $style);*/
    $contenuto = file_get_contents("../html/eventi.html");
    $contenuto = str_replace("%LISTA_EVENTI%", $lista, $contenuto);
    $pagina = Genera_pagina::genera("../html/base.html", "eventi", $contenuto);
    echo $pagina;
  }
  else {
    header('Location: redirect.php?error=1');
    exit;
  }
?>

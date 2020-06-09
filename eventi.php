<?php
  header('Content-Type: text/html; charset=UTF-8');

  require_once("DBAccess.php");
  require_once("Image.php");

  $con = new DBAccess();
  if($con->openConnection()){
    if (!$con->connection->set_charset("utf8")){
      //printf("Error loading character set utf8: %s\n", $con->error);
      exit;
    }

    $pagina = file_get_contents('eventi.html');
    $style = file_get_contents('stylesheet.css');
    $lista_eventi = $con->getEventi();
    $lista_descrizione = $con->getDescrizione_eventi();

    //necessario se il locale non è ancora impostato
    setlocale(LC_TIME, "it_IT");

    foreach ($lista_eventi as $row){
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
      foreach ($lista_descrizione as $row_descr){
        if($row_descr["evento"]==$row["id_evento"]){
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

      $backgroundImg = "\n" . '#n' . $row['id_evento'] . '{
        background-image: url(' . $immagine . ');
      }' . "\n";

      if(!strpos($style, $backgroundImg)) {
        $style .= $backgroundImg;
      }

      $lista .=
      '<div id= ' . $row['id_evento'] . ' class="card eventi">
          <span class="data">'.$giorno_testo.' <span>'.$giorno_numero.'</span> '.$mese.'</span>
          <img src="'.$immagine.'" alt="'.$descrizione_immagine.'"/>
          <h3 class="titoletto">'.$titolo.'</h3>
          <ul>
            '.$descrizione_formattata.'
          </ul>
          <h3 class="titoletto">Relatori</h3>
          <p>'.$relatori.'</p>
          <h3 class="titoletto">Mappa e data</h3>
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

    file_put_contents('stylesheet.css', $style);
    $pagina = str_replace("%LISTA_EVENTI%", $lista, $pagina);
    echo $pagina;
  }
  else{
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit;
  }
?>

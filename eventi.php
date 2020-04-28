<?php
  require_once("DBAccess.php");
  require_once("Image.php");

  header('Content-Type: text/html; charset=UTF-8');

  $con = new DBAccess();
  if($con->openConnection()){
    if (!$con->connection->set_charset("utf8")){
      //printf("Error loading character set utf8: %s\n", $con->error);
      exit;
    }

    $pagina = file_get_contents('eventi.html');

    $lista_eventi = $con->getEventi();
    foreach ($lista_eventi as $row){
      $data_ora = new DateTime($row["data_ora_evento"]);
      /* formati usati per data e ora:
      *    data:
      *      l = giorno testuale; d = giorno numerale; F = mese testuale;
      *    ora:
      *      H:i = ore:minuti;
      *  maggiori informazioni sulle guide:
      *    generic - https://www.php.net/manual/en/datetime.formats.php
      *    date - https://www.php.net/manual/en/datetime.formats.date.php
      *    time - https://www.php.net/manual/en/datetime.formats.time.php
      */

      $immagine = Image::getImage("./img/eventi/", $row["id_evento"]);
      $descrizione_immagine = htmlentities($row["descrizione_immagine_evento"]);
      $titolo = htmlentities($row["titolo_evento"]);
      // $descrizione;
      $relatori = nl2p(htmlentities($row["relatore_evento"]));
      $indirizzo_mappa = htmlentities($row["indirizzo_mappe_evento"]);
      $url_mappa = $row["url_mappe_evento"];
      $descrizione_mappa = nl2p(htmlentities($row["descrizione_mappe_evento"]));
      $organizzazione = nl2p(htmlentities($row["organizzazione_evento"]));
      $posti_limitati = $row["prenotazione_posti_evento"];

      $lista .=
        '<div class="card eventi">
          <div class= "tolgoLineaBianca">
            <div class="databox">
              <p class="data">'.$data_ora->format("l").' <span>'.$data_ora->format("d").'</span> '.$data_ora->format("F").'</p>
            </div>
            <div class="imgwrap">
              <img src="'.$immagine.'" alt="'.$descrizione_immagine.'"/>
            </div>
          </div>
          <h3 class="titoletto">'.$titolo.'</h3>
          <ul>
            descrizione_TO_DO
          </ul>
          <h3 class="titoletto">Relatori</h3>
          <p>'.$relatori.'</p>
          <h3 class="titoletto">Mappa e data</h3>
            <a id="linkMappa" href="'.$url_mappa.'">'.$indirizzo_mappa.'</a>
            <p>'.$descrizione_mappa.'</p>
          <p id="dataEvento">
            '.$data_ora->format("l d F").' - ore '.$data_ora->format("H:i").'
          <p>
          <p id="org">
            '.$organizzazione.'
          </p>
          '.($posti_limitati ? '<p id="prenotazione">
                                  <span>I posti sono limitati, &egrave; gradita la prenotazione</span> (i contatti si trovano <a href="pagina_informazioni.html#contatti">qui</a>)
                                </p>' : "").'
        </div>

          ';
      }
      /*
      $lista .=
        '<button class="card collapsed">
          <img src="'.$immagine.'" alt="'.$descrizione_immagine.'"/>
          <dl>
            <dt><h3>'.$nome.'</h3></dt>
            <dd>
              <h4>Ingredienti</h4>
              <p>'.$ingredienti.'</p>
              <h4>Descrizione</h4>
              <p>'.$descrizione.'</p>
              <h4>Preparazione</h4>
              <p>'.$preparazione.'</p>
            </dd>
          </dl>
          <i class="material-icons-round">expand_more</i>
        </button>
        ';
    }
    */
    $pagina = str_replace("%LISTA_EVENTI%", $lista, $pagina);
    echo $pagina;
  }
  else{
    echo "<h1>Impossibile connettersi al database riprovare pi&ugrave; tardi<h1>";
    exit;
  }

  /* mette i tag di paragrafo ad ogni nuova riga */
  function nl2p($text){
    return str_replace(array("\r\n", "\r", "\n"), "</p><p>", $text);
  }
?>

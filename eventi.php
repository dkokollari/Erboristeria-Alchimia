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
      // $data;
      $immagine = Image::getImage("./img/eventi/", $row["id_evento"]);
      $descrizione_immagine = htmlentities($row["descrizione_immagine_evento"]);
      $titolo = htmlentities($row["titolo_evento"]);
      // $descrizione;
      $relatori = nl2p(htmlentities($row["relatore_evento"]));
      $indirizzo_mappa = htmlentities($row["indirizzo_mappe_evento"]);
      $url_mappa = $row["url_mappe_evento"];
      $descrizione_mappa = nl2p(htmlentities($row["descrizione_mappe_evento"]));
      // $ora;
      $organizzazione = nl2p(htmlentities($row["organizzazione_evento"]));
      $posti_limitati = $row["prenotazione_posti_evento"];

      $lista .=
        '<div class="card eventi">
          <div class= "tolgoLineaBianca">
            <div class="databox">
              <p class="data">nulledi <span>null</span> nullobre</p>
            </div>
            <div class="imgwrap">
              <img src="" alt=""/>
            </div>
          </div>
          <h3 class="titoletto"></h3>
          <ul>
            descrizione_TO_DO
          </ul>
          <h3 class="titoletto">Relatori</h3>
          <h3 class="titoletto">Mappa e data</h3>
            <a id= "linkMappa" href= ""></a>
            <p></p>
          <p id="dataEvento">

          <p>
          <p id="org">

          </p>
          <p id="prenotazione">
          <span>I posti sono limitati, &egrave; gradita la prenotazione</span> (i contatti
            si trovano <a href= "pagina_informazioni.html#contatti">qui</a>)
          </p>
        </div>

          ';
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

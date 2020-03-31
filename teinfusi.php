<!doctype html>
<html lang="en" >
  <head>
                        <!-- DA RIVEDERE TITOLO E ICONA  -->
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <title>Te e Infusi - Erboristeria Alchimia</title>
    <link rel="stylesheet" href="stylesheet.css"></link>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,600&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  </head>

  <body>
                          <!--          HEADER             -->
      <div id="menu">
        <button><i class="material-icons-round" onclick="showMenu()">cancel</i></button>
        <ul>
          <li><a href="homepage.html">Homepage</a></li>
          <li><a href="eventi.html">Eventi</a></li>
          <li><a href="no.html">Te e Infusi</a></li>
          <li><a href="prodotti.html">I miei Prodotti</a></li>
          <li><a href="pagina_informazioni">Informazioni</a></li>
        </ul>
      </div>
      <button id="right-of-menu" onclick="showMenu()">

      </button>
      <button><i id="menu_icon" class="material-icons-round" onclick="showMenu()">menu</i></button>
      <div id="topbar">
        <h1 id="topbar_title">T&egrave; & infusi</h1>
      </div>

      <div id="header_image"></div>
      <h1>T&egrave; e Infusi</h1>
                          <!--         CONTENT            -->
      <div id="content">
        <h2>Una passione per gli infusi</h2>
        <p>
          In questa pagina potete trovare le mie composizioni di t&egrave; e infusi che ho preparato per voi ponendo attenzione alle varie propriet&agrave; e sapori.
          Queste sono solo alcune delle mie proposte, ma &egrave; sempre possibile richiedere un mix personalizzato che pi&ugrave; si addice ai vostri gusti.
        </p>
                          <!--          CONSIGLI DI MARIKA            -->
        <div class="tips_and_tricks collapsed">
          <!-- <i class="material-icons-round">emoji_food_beverage</i> -->
          <h3>Il consiglio di Marika</h3>
          <!-- <i class="material-icons-round">emoji_food_beverage</i> -->
          <h4>Le 5 regole d'oro per preparare un buon t&egrave;</h4>
          <p>Preparare un buon t&egrave; &egrave; semplice se si tengono a mente alcune regole:</p>
          <ul>
            <li><span>La qualita dell'acqua &egrave; importante ai fini del gusto</span>: utilizzare acqua a basso residuo fisso, dolce, poco calcarea</li>
            <li><span>Utilizzare un infusore ad immersione capiente,</span> in modo che le foglie di t&egrave; possano aprirsi e liberare il profumo ed il gusto o in alternativa filtrare in una seconda teiera</li>
            <li><span>Utilizzare l'acqua alla giusta temperatura secondo le categorie del t&egrave;.</span> Attenzione i t&egrave; verdi e bianchi vogliono una temperatura di unfusione tra i 70 e gli 80 &#8451;. In questo modo risultano morbidi e soavi in tazza</li>
            <li><span>Versare l'acqua sulle foglie di t&egrave;</span></li>
            <li><span>Lasciare il te in infusione per il corretto tempo,</span> secondo le indicazioni relative alla categoria (chiedetemi pure in erboristeria)</li>
          </ul>
          <i class="material-icons-round">expand_more</i>
        </div>

                          <!--          LISTA DI INFUSI            -->
          <?php

          /* mette i tag di paragrafo ad ogni nuova riga */
          function nl2p($text) {
              return str_replace(array("\r\n", "\r", "\n"), "</p><p>", $text);
          }

          /* connessione con hostname, username, password, database */
          $con = mysqli_connect("localhost","erboristeriatest","","my_erboristeriatest");
          if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: ".mysqli_connect_error();
            exit();
          }

          /* visualizza il set di caratteri utilizzato dal client */
          //printf("Initial character set: %s\n", $con->character_set_name());
          /* cambia il set di caratteri in utf8 */
          if (!$con->set_charset("utf8")) {
              //printf("Error loading character set utf8: %s\n", $con->error);
              exit();
          }

          $query_visualizza_te_e_infusi = "SELECT id_te_e_infusi, nome_te_e_infusi, ingredienti_te_e_infusi, descrizione_te_e_infusi, preparazione_te_e_infusi FROM te_e_infusi";

          if ($result = mysqli_query($con, $query_visualizza_te_e_infusi)) {
            while ($row = mysqli_fetch_assoc($result)) {
              print (
                '<div class="card collapsed">'."\n".
                //controllo se l'immagine esiste (importante che sia in formato jpg) e in caso la visualizzo, altrimenti mostro un immagine statica presente nella directory
                ' <img src="img/te_e_infusi/'.(file_exists("img/te_e_infusi/".$row["id_te_e_infusi"].".jpg") ? $row["id_te_e_infusi"].'.jpg' : '0.jpg').'"/>'."\n". 
                ' <h3>'.htmlentities($row["nome_te_e_infusi"], ENT_NOQUOTES).'</h3>'."\n".
                '   <h4>Ingredienti</h4>'."\n".
                '   <p>'.nl2p(htmlentities($row["ingredienti_te_e_infusi"], ENT_NOQUOTES)).'</p>'."\n".
                '   <h4>Descrizione</h4>'."\n".
                '   <p>'.nl2p(htmlentities($row["descrizione_te_e_infusi"], ENT_NOQUOTES)).'</p>'."\n".
                '   <h4>Preparazione</h4>'."\n".
                '   <p>'.nl2p(htmlentities($row["preparazione_te_e_infusi"], ENT_NOQUOTES)).'</p>'."\n".
                ' <i class="material-icons-round">expand_more</i>'."\n".
                '</div>'."\n"
              );
              }
            mysqli_free_result($result);
          }

          mysqli_close($con);
          ?>

        </dl>
        </div>



<!--          FOOTER            -->
<div id="footer">
I piedi in doccia si lavano da soli con l'acqua che cade
</div>


</body>
<script>

const headerImg = document.querySelector("#header_image");
const content = document.querySelector("#content");
const topbar = document.querySelector("#topbar");
const topbarTitle = document.querySelector("#topbar_title");
const menu = document.querySelector("#menu");
const rightofmenu= document.querySelector("#right-of-menu");
const marginTop = document.documentElement.clientWidth*0.63;  // 75vw di margin-top del content - 12vw di altezza topbar
var yScrollPosition;
var i;
var j;


function showMenu() {
menu.classList.toggle("showmenu");
rightofmenu.classList.toggle("hidemenu");
}







window.addEventListener("DOMContentLoaded", scrollLoop, false);

function scrollLoop() {
yScrollPosition = window.scrollY;
headerImg.style.transform = "translate3d(" + 0 + ", " + yScrollPosition*-0.45 + "px, 0)";
requestAnimationFrame(scrollLoop);
}
window.addEventListener('scroll', showTopbar);
function showTopbar() {
if (document.documentElement.scrollTop > marginTop) {
topbar.style.zIndex = "2";                                //controllare che gli vada bene
topbarTitle.classList.add("nopacity");
} else {
topbar.style.zIndex = "-2";                             //controllare che vada bene
topbarTitle.classList.remove("nopacity");
}
}



// const titolo = document.getElementById("titolo");                // titolo in corsivo
// const titlebar = document.getElementById("topbar_title");        // titolo nella barra in alto
// const topvar = document.getElementById("topbar");                // barra in alto da fissare
//
// const scro = document.getElementById("content");


//         INAFFIDABILE, OGNI TANTO LAGGA
// $(document).ready(function() {
//      $('#wrapper').scroll(function(event) {
//          var scroll = scro.scrollTop;
//          let opacity = 1 - (scroll / 200);
//          if (opacity > -0.1) {
//              $('#titolo').css('opacity', opacity);
//          }
//          if (-opacity < 1) {
//              $('#topbar_title').css('opacity', 1-opacity);
//          }
//
//      });
//  });

// scro.addEventListener('scroll', topbar);
// function topbar() {
//    scro.scrollTop > 210 ? topvar.classList.remove("hide") : topvar.classList.add("hide");
//    scro.scrollTop > 20  ? titolo.classList.add("nopacity") : titolo.classList.remove("nopacity");
//    scro.scrollTop > 20  ? titlebar.classList.add("opacity") : titlebar.classList.remove("opacity");
// }

const tips = document.getElementsByClassName("tips_and_tricks");
const card = document.getElementsByClassName("card");

for (i = 0; i < tips.length; i++) {
tips[i].addEventListener('click', expandTips);
tips[i].addEventListener('keydown', (event) => {
if (event.code === 'Space' || event.code === 'Enter') {
event.target.click();
}
});
}

function expandTips() {
this.classList.toggle("collapsed");
this.lastElementChild.classList.toggle("rotated");
}

for (j = 0; j < card.length; j++) {
card[j].addEventListener('click', expandCard);
card[j].addEventListener('keydown', (event) => {
if (event.code === 'Space' || event.code === 'Enter') {
event.target.click();
}
});
}
function expandCard() {
this.classList.toggle("collapsed");
this.lastElementChild.classList.toggle("rotated");
}
// function expand(){
//   this.firstElementChild.lastElementChild.classList.toggle("expandedcard");
//   this.firstElementChild.firstElementChild.classList.toggle("expandedimg");
//   this.lastElementChild.classList.toggle("rotate");
// }


</script>

</html>

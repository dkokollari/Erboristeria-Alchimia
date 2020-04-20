<!DOCTYPE html>
<HTML lang="en">
  <HEAD>
    <!-- DA RIVEDERE TITOLO E ICONA  -->
    <title>Ricerca articoli - Erboristeria Alchimia</title>
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png"/>
    <link rel="stylesheet" href="stylesheet.css"/>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  </HEAD>
  <BODY>
    <!--          HEADER             -->
    <button>
      <i id="menu_icon" class="material-icons-round">menu</i>
    </button>
    <div id="topbar">
      <h1 id="topbar_title">T&egrave; & infusi</h1>
    </div>
    <div id="header_image"></div>
    <h1>Ricerca articoli</h1>

    <!--         CONTENT            -->
    <div id="content">
      <h2>Trova ci&ograve; che ti serve</h2>

      <!--      https://www.w3schools.com/howto/howto_js_filter_lists.asp      -->
      <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Inserisci il nome.." title="Inserisci un nome">

      <ul id="myUL">

        <?php
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

        $query_ricerca = "SELECT id_articolo, nome_articolo, quantita_magazzino_articolo FROM articoli ORDER BY id_articolo DESC";

        if ($result = mysqli_query($con, $query_ricerca)) {
          while ($row = mysqli_fetch_assoc($result)) {
            print (
              '<li><a>'.htmlentities($row[nome_articolo], ENT_NOQUOTES).'</a> <div id="disponibilita>'.$row[quantita_magazzino_articolo].' disponibili</div></li>
        '
            );
          }
          mysqli_free_result($result);
        }

        mysqli_close($con);
        ?>

      </ul>
    </div>

    <!--          FOOTER            -->
    <div id="footer"> I piedi in doccia si lavano da soli con l'acqua che cade </div>

    <script>
      function myFunction() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
          a = li[i].getElementsByTagName("a")[0];
          txtValue = a.textContent || a.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
          }
          else {
            li[i].style.display = "none";
          }
        }
      }
    </script>

  </BODY>
</HTML>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                          <!-- DA RIVEDERE TITOLO E ICONA  -->
      <title>Inserimento te e infusi - Erboristeria Alchimia</title>

      <meta name="description" content="Form di inserimento te e infusi"/>
      <meta name="language" content="italian it"/>
      <meta name="author" content="Erboristeria Alchimia"/>

      <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png"/>
    <!--  <link rel="stylesheet" href="stylesheet.css"/> -->
      <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,600&display=swap" rel="stylesheet"/>

      <link href="https://fonts.googleapis.com/css?family=Material+Icons%7CMaterial+Icons+Outlined%7CMaterial+Icons+Two+Tone%7CMaterial+Icons+Round%7CMaterial+Icons+Sharp" rel="stylesheet"/>
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

      <!--<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>-->


    </head>
    <body>
      <?php

      //CONNESSIONE AL DATABASE
      $con = mysqli_connect("localhost","erboristeriatest","","my_erboristeriatest");
      if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: ".mysqli_connect_error();
        exit();
      }

     //se e' stato premuto il buttone submit
      $image =NULL;
      //controllo se l'immagine è stata caricata
       if(is_uploaded_file($_FILES['immagine']['tmp_name'])){
        //verifica l'estensione del file caricato
          $ext_ok = array('jpg', 'jpeg', 'png');
        $temp = explode('.',$_FILES['immagine']['name']);
        $ext = array_pop($temp);
        if(in_array($ext, $ext_ok)){
          $ima = $_FILES['immagine']['name'];
          $dir = "img/";
          $tmp = $_FILES['immagine']['tmp_name'];
          if(move_uploaded_file($tmp,$dir.$ima)){
            $path = $dir.$ima;
          //  $type = pathinfo($path,PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $image = base64_encode($data);
          }
        }
        else{
        echo "formato file errato";
          ///////////////////////////
          /////  DA  FARE !!! ///////
          ///////////////////////////
        }
      }
      $tipo = trim($_POST['Tipo']);
      $nome = trim($_POST['Nome']);
      $ingre = trim($_POST['Ingredienti']);
      $descr = trim($_POST['Descrizione']);
      $prepa = trim($_POST['Preparazione']);

      if($tipo!="" && $nome!="" && $ingre!="" && $descr!="" && $prepa!=""){

        //QUERY DI INSERIMENTO

         $insert = "INSERT INTO te_e_infusi (immagine_te_e_infusi, tipo_te_e_infusi, nome_te_e_infusi,ingredienti_te_e_infusi, descrizione_te_e_infusi,preparazione_te_e_infusi) VALUES ('$image','$tipo','$nome','$ingre','$descr','$prepa')";
        if($res = mysqli_query($con,$insert)){
          echo "Query eseguita " ;
        }else {
        echo " ////////////////////// ERRORE ////////////////////////";
		    }
      }
      else{
        echo "caselle vuote";
      }
      mysqli_close($con);
       ?>
    </body>
</html>

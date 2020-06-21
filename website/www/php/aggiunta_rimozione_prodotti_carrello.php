<?php
  require_once("session.php");
  require_once("Utilities.php");

  $orderedProducts = '';
  if(isset($_SESSION['email_utente']) ||
      (isset($_COOKIE['email']) && isset($_COOKIE['password']))) {
    $product_ids = array();
    $qtaLastProductAdded=1;
    if(isset($_GET['add_to_cart'])) {
      if(isset($_SESSION['shopping_cart'])) {
        $count = count($_SESSION['shopping_cart']);
        $product_ids = array_column($_SESSION['shopping_cart'], 'id_articolo');

        if(!in_array(Utilities::getNumericValue('id_articolo'), $product_ids)) {
          $_SESSION['shopping_cart'][$count] = array
            ( 'id_articolo' => Utilities::getNumericValue('id_articolo')
            , 'nome_articolo' => $_GET['nome_articolo']
            , 'prezzo_articolo' => $_GET['prezzo_articolo']
            , 'quantita' => 1
            );
        }
        else {
          $flag = false;
          for($i=0; !$flag && $i < count($product_ids); $i++) {
            if($product_ids[$i] == Utilities::getNumericValue('id_articolo')) {
              $qtaLastProductAdded=$_SESSION['shopping_cart'][$i]['quantita'] += 1;
              $flag = true;
            }
          }
        }
      } //endif isset($_SESSION['shopping_cart'])
      else {
        $_SESSION['shopping_cart'][0] = array
          ( 'id_articolo' => Utilities::getNumericValue('id_articolo')
          , 'nome_articolo' => $_GET['nome_articolo']
          , 'prezzo_articolo' => Utilities::getNumericValue('prezzo_articolo')
          , 'quantita' => 1
          );
      }
      $redirect = 'prodotto_singolo.php?addedProduct&id_articolo='.Utilities::getNumericValue('id_articolo'). "&qta=$qtaLastProductAdded";
      header('location : ' . $redirect);
      echo $pagina;
    }
  }

  if($_GET['action'] == 'delete') {
    foreach($_SESSION['shopping_cart'] as $key => $product) {
      if($product['id_articolo'] ==  Utilities::getNumericValue('id_articolo')) {
        $_SESSION['valAcquisto'] -= $product['prezzo_articolo'];
        unset($_SESSION['shopping_cart'][$key]);
      }
    }
   $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
  }
?>

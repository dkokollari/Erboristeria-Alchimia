<?php
class Errore {

  public static function getErrore($err) {

    $msg='';
    switch($err) {
      case '1' :
         $msg = 'Impossibile connettersi al <span xml:lang="en">database</span>, riprovare pi&ugrave; tardi';
         break;

     case '2' :
        $msg = '404 : pagina non trovata';
        break;

      case '3' :
        $msg = '403 : la risorsa a cui cerca di accedere non &egrave; disponibile';
        break;

     return $msg; 
    }
  }
}
 ?>

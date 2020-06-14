<?php
  class Errore {
    public static function getErrore($err) {
      $msg='';
      switch($err) {
        case 1 :
           $msg = 'Errore : Impossibile connettersi al <span xml:lang="en">database</span>, riprovare pi&ugrave; tardi';
           break;

       case 2 :
          $msg = 'Errore 404 : pagina non trovata';
          break;

        case 3 :
          $msg = 'Errore 403 : la risorsa a cui cerca di accedere non &egrave; disponibile';
          break;

        case 4 :
          $msg = 'Errore : Impossibile eseguire la <span xml:lang="en">query</span>, riprovare pi&ugrave; tardi';
          break;
      }
     return $msg;
    }
  }
?>

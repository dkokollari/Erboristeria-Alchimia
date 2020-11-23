<?php
require_once ("session.php");
require_once ("DBAccess.php");
require_once ("genera_pagina.php");
require_once ("validate_form.php");

if ($_SESSION['auth'])
{
  $con = new DBAccess();
  if (!$con->openConnection())
  {
    header('Location: redirect.php?error=1');
    exit;
  }

  if ($_POST['Elimina_profilo'])
  {
    $con->deleteUtenti($_SESSION['email_utente']);
    header('Location: logout.php');
  } // end if $_POST['Elimina_profilo']
  if ($_POST['Modifica_profilo'])
  {
    $nome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['nome']))));
    $cognome = ucfirst(strtolower(mysql_real_escape_string(trim($_POST['cognome']))));
    $email = mysql_real_escape_string(trim($_POST['email']));
    $password = mysql_real_escape_string(trim($_POST['password']));
    $password_conferma = mysql_real_escape_string(trim($_POST['password_conferma']));
    $data_nascita = mysql_real_escape_string(trim($_POST['data_nascita']));
    /* messaggi di errore */
    $errore_empty = '<span class="errore">Completa tutti i campi</span>';
    $errore_full = '<span class="errore">Questa e-mail sembra non essere disponibile</span>';
    $errore_nome = '<span class="errore">Inserisci un nome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
    $errore_cognome = '<span class="errore">Inserisci un cognome di lunghezza tra 3 e 100 caratteri</span>'; // riferirsi alle regole di validate_form
    $errore_email = '<span class="errore">Inserisci una email valida</span>';
    $errore_password = '<span class="errore">Inserisci una password di lunghezza tra 8 e 100 caratteri, almeno 1 lettera ed 1 numero</span>'; // riferirsi alle regole di validate_form
    $errore_conferma = '<span class="errore">Le password inserite non corrispondono</span>';
    $errore_sconosciuto = '<span class="errore">Per favore disconnettiti e accedi di nuovo</span>';

    $fields = [$email, $password, $data_nascita];
    if (Validate_form::is_empty($fields)) $errore = $errore_empty;
    else if (!empty($nome) && !Validate_form::check_str($nome)) $errore = $errore_nome;
    else if (!empty($cognome) && !Validate_form::check_str($cognome)) $errore = $errore_cognome;
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errore = $errore_email;
    else if (!Validate_form::check_pwd($password)) $errore = $errore_password;
    else if ($password != $password_conferma) $errore = $errore_conferma;
    else if ($email != $_SESSION['email_utente'] && $con->getSingolo_Utenti($email)) $errore = $errore_full;
    else
    {
      $result = $con->updateUtenti($_SESSION['email_utente'], $nome, $cognome, $email, $password, $data_nascita);
      if (!$result) $errore = $errore_sconosciuto;
      else
      {
        $array = $con->getSingolo_Utenti($email);
        $_SESSION['nome_utente'] = $array[0]['nome_utente'];
        $_SESSION['cognome_utente'] = $array[0]['cognome_utente'];
        $_SESSION['email_utente'] = $array[0]['email_utente'];
        $_SESSION['password_utente'] = $array[0]['password_utente'];
        $_SESSION['data_nascita_utente'] = $array[0]['data_nascita_utente'];
      }
    }

    $status = (empty($errore) ? '<span class="success">Profilo aggiornato con successo</span>' : $errore);
  } // end if $_POST['Modifica_profilo']
  // prelievo tessera utente e visualizzazione messaggi
  if ($_SESSION['tipo_utente'] == 'User')
  {
    $minPrezzoTimbro = 10; // prezzo acquisto che dà diritto ad un timbro ("effettivamente final")
    $num_timbri = $con->getTimbriUtente($_SESSION['email_utente']) [0]['numero_timbri_utente'];
    $num_buoni = $num_timbri / 20; /*20 e' il numero massimo di buoni per carta fedelta*/
    $avvisoCartaPiena = '<p class="success">Hai riempito almeno una carta fedelt&agrave;&#33;
    recati in negozio e sfrutta i tuoi buoni per i prossimi acquisti, ti aspettiamo&#33;</p>';
    $img_timbri =
      ($num_buoni >= 1) ? '<img class="carta_fedelta" src="../img/carta_fedelta/20.png"
      alt="la tua carta fedelt&agrave;: hai ' .$num_timbri . ' timbri"/>' :
      '<img class="carta_fedelta" src="../img/carta_fedelta/' . $num_timbri . '.png"'
        . ' alt="la tua carta fedelt&agrave;: hai ' .$num_timbri . ' timbri"/>';
    $compleanno = DateTime::createFromFormat("Y-m-d", $_SESSION['data_nascita_utente']);
    if ($compleanno->format('d') == date('d') && $compleanno->format('m') == date('m'))
    {
      $auguri = '<p class="success">
                     Tanti auguri di buon compleanno da Erboristeria Alchimia, ' . $_SESSION['nome_utente'] . '&#33; <br/>
                     Per la giornata di oggi, hai diritto ad uno sconto di 10&euro; su un prodotto a tua scelta: corri in negozio, ti aspettiamo. <br/>
                     Ci teniamo a farti gli auguri di persona&#33;
                   </p>';
    }
    if (isset($_SESSION['valAcquisto']) && !empty($_SESSION['valAcquisto']))
    {
      $_SESSION["shopping_cart"] = null; // svuoto il carrello
      $aggTimbri = '<p class="success">Grazie per il tuo acquisto&#33;</p>';
      if ($_SESSION['valAcquisto'] / $minPrezzoTimbro > 0)
      {
        $num_timbri += (int)($_SESSION['valAcquisto'] / $minPrezzoTimbro);
        $con->updateTimbriSingolo_Utenti($_SESSION['email_utente'], $num_timbri);
        $aggTimbri = '<p class="success">
                          Grazie per il tuo acquisto&#33; Ti sono state riempite delle caselle nella tua carta fedelt&agrave;: quando la tua carta sar&agrave; piena, recati in negozio per sfruttarla come buono da 15&euro;.
                        </p>';
        $_SESSION['valAcquisto'] = null;                
      }
    }
  } // end if $_SESSION['tipo_utente'] == 'User'
  $con->closeConnection();
  $contenuto = file_get_contents("../html/profilo.html");
  $contenuto = str_replace("%AGG_TIMBRI%", $aggTimbri, $contenuto);
  $contenuto = str_replace("%NUMERO_TIMBRI%", $num_timbri, $contenuto);
  $contenuto = ($num_buoni >=1) ?
   str_replace("%CARTA_PIENA%", $avvisoCartaPiena, $contenuto) :
      str_replace("%CARTA_PIENA%", '', $contenuto);
  $contenuto = str_replace("%IMG_CARTA%", $img_timbri, $contenuto);
  $contenuto = str_replace("%AUGURI%", $auguri, $contenuto);
  $contenuto .= file_get_contents("../html/form_utente.html");
  $contenuto = str_replace("%ACTION_FORM%", "profilo.php", $contenuto);
  $contenuto = str_replace("%TITOLO%", "Modifica i dati", $contenuto);
  $contenuto = str_replace("%STATO_UTENTE%", $status, $contenuto);
  // pre-fill input, riempimento campi
  $array_place_html = ['nome', 'cognome', 'email', 'data_nascita'];
  $array_place_session = ['nome_utente', 'cognome_utente', 'email_utente', 'data_nascita_utente'];
  foreach (array_combine($array_place_html, $array_place_session) as $html => $session)
  {
    if ($_SESSION[$session] != "") $contenuto = str_replace('<label for="' . $html . '">', '<label class="filled" for="' . $html . '">', $contenuto);
    $contenuto = str_replace('%VALUE_' . $html . '%', 'value="' . $_SESSION[$session] . '"', $contenuto);
  }
  $contenuto = str_replace("%NOME_SUBMIT%", "Modifica_profilo", $contenuto);
  $contenuto = str_replace("%AGGIUNGI_SUBMIT%", '<input id="delete_user" type="submit" value="Elimina il profilo" name="Elimina_profilo"/>', $contenuto);
  $pagina = Genera_pagina::genera("../html/base5.html", "profilo", $contenuto);
  echo $pagina;
} // end if $_SESSION['auth']
else
{
  header('Location: redirect.php?error=3');
  exit;
}
?>

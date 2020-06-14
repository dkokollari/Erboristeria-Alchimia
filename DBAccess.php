<?php
  class DBAccess {
    const HOST_DB = 'localhost';
    const USER_NAME = 'erboristeriatest';
    const PASSWORD = '';
    const DB_NAME = 'my_erboristeriatest';

    public $connection = null;

    ####################################
    # gestione connessione al database #
    ####################################

    public function openConnection() {
      $this->connection = mysqli_connect(static::HOST_DB, static::USER_NAME, static::PASSWORD, static::DB_NAME);
      if($this->connection) {
        if($this->connection->set_charset("utf8")) return true;
      }
      return false;
    }

    public function closeConnection() {
      mysqli_close($this->connection);
    }

    #################################################################################
    # gestione te & infusi (inserimento, aggiornamento, rimozione, visualizzazione) #
    #################################################################################

    public function insertTeInfusi($descImg, $tipo, $nome, $ingre, $descr, $prepa) {
      $query = ($descImg != ""
               ? "INSERT INTO `te_e_infusi` (`descrizione_immagine_te_e_infusi`,
                                             `tipo_te_e_infusi`,
                                             `nome_te_e_infusi`,
                                             `ingredienti_te_e_infusi`,
                                             `descrizione_te_e_infusi`,
                                             `preparazione_te_e_infusi`)
                                     VALUES ('".$descImg."',
                                             '".$tipo."',
                                             '".$nome."',
                                             '".$ingre."',
                                             '".$descr."',
                                             '".$prepa."')"
               : "INSERT INTO `te_e_infusi` (`tipo_te_e_infusi`,
                                             `nome_te_e_infusi`,
                                             `ingredienti_te_e_infusi`,
                                             `descrizione_te_e_infusi`,
                                             `preparazione_te_e_infusi`)
                                     VALUES ('".$tipo."',
                                             '".$nome."',
                                             '".$ingre."',
                                             '".$descr."',
                                             '".$prepa."')");
      return (mysqli_query($this->connection, $query) ? true : false);
    }

    public function updateTeInfusi($id, $nome, $tipo, $ingre, $desc, $prepa, $descImg) {
      $query = ($descImg != ""
               ? "UPDATE `te_e_infusi`
                   SET `descrizione_immagine_te_e_infusi`= '".$descImg."',
                       `tipo_te_e_infusi`='".$tipo."',
                       `nome_te_e_infusi`='".$nome."',
                       `ingredienti_te_e_infusi`='".$ingre."',
                       `descrizione_te_e_infusi`='".$desc."',
                       `preparazione_te_e_infusi`='".$prepa."'
                 WHERE `id_te_e_infusi` = '".$id."'"
                 : "UPDATE `te_e_infusi`
                     SET `tipo_te_e_infusi`='".$tipo."',
                         `nome_te_e_infusi`='".$nome."',
                         `ingredienti_te_e_infusi`='".$ingre."',
                         `descrizione_te_e_infusi`='".$desc."',
                         `preparazione_te_e_infusi`='".$prepa."'
                   WHERE `id_te_e_infusi` = '".$id."'");
      return (mysqli_query($this->connection, $query) ? true : false);
    }

    public function deleteTeInfusi_by_id($id) {
      $query = "DELETE FROM `te_e_infusi`
                      WHERE `id_te_e_infusi` = '".$id."'";
      return (mysqli_query($this->connection, $query) ? true : false);
    }

    public function getTeInfusi(){
      $query = "SELECT `id_te_e_infusi`,
                       `descrizione_immagine_te_e_infusi`,
                       `nome_te_e_infusi`,
                       `ingredienti_te_e_infusi`,
                       `descrizione_te_e_infusi`,
                       `preparazione_te_e_infusi`
                FROM   `te_e_infusi`";
      return $this->getQuery($query);
    }

    # getters te & infusi #

    public function getId_TeInfusi($name) {
      $result = "errore";
      $query = "SELECT `id_te_e_infusi`
                  FROM `te_e_infusi`
                 WHERE `nome_te_e_infusi`= '".$name."'";
      if($res = mysqli_query($this->connection, $query)){
        $row = mysqli_fetch_array($res);
        $result = $row['id_te_e_infusi'];
      }
      return $result;
    }

    public function getSingoloTeInfuso($id) {
      $query = "SELECT `id_te_e_infusi`,
                       `descrizione_immagine_te_e_infusi`,
                       `tipo_te_e_infusi`,
                       `nome_te_e_infusi`,
                       `ingredienti_te_e_infusi`,
                       `descrizione_te_e_infusi`,
                       `preparazione_te_e_infusi`
                FROM   `te_e_infusi`
                WHERE  `id_te_e_infusi`=?";
      $types = "i";
      $params = [$id];
      return $this->getQuery($query, $types, $params);
    }

    #####################################
    # gestione eventi (visualizzazione) #
    #####################################

    public function insertEvento($descImg, $titolo, $data, $relatori, $mappa, $org) {
      $query = "INSERT INTO `eventi` (`data_ora_evento`,
                                     `descrizione_immagine_evento`,
                                     `titolo_evento`,
                                     `relatore_evento`,
                                     `mappa_evento`,
                                     `organizzazione_evento`)
                             VALUES (?, ?, ?, ?, ?)";
      $types = "sssss";
      $params = [$data, $descImg, $titolo, $relatori, $mappa, $org];
      return $this->getQuery($query, $types, $params, false);
    }

    public function getId_Evento($titolo) {
      $result = "errore";
      $query = "SELECT `id_evento`
                  FROM `eventi`
                 WHERE `titolo_evento`= '".$titolo."'";
      if($res = mysqli_query($this->connection, $query)) {
        $row = mysqli_fetch_array($res);
        $result = $row['id_evento'];
      }
      return $result;
    }

    public function insertDescrizioneEventi($id, $stt) {
      $query = "INSERT INTO `descrizione_eventi` (`evento`,
                                                  `sottotitolo`)
                                          VALUES (?,?)";
      $types = "ss";
      $params = [$id, $stt];
      return $this->getQuery($query, $types, $params, false);
    }

    public function insertMappaEventi($mappa, $desc_map) {
      $query = "INSERT INTO `mappe_eventi` (`indirizzo_mappe_evento`,
                                            `descrizione_mappe_evento`)
                                    VALUES (?,?)";
      $types = "ss";
      $params = [$mappa, $desc_map];
      return $this->getQuery($query, $types, $params, false);
    }

    public function getEventi() {
      $query = "SELECT `id_evento`,
                       `data_ora_evento`,
                       `descrizione_immagine_evento`,
                       `titolo_evento`,
                       `relatore_evento`,
                       `indirizzo_mappe_evento`,
                       `url_mappe_evento`,
                       `descrizione_mappe_evento`,
                       `organizzazione_evento`,
                       `prenotazione_posti_evento`
                  FROM `eventi`,
                       `mappe_eventi`
                 WHERE `mappa_evento` = `indirizzo_mappe_evento`";
      return $this->getQuery($query);
    }

    # getters eventi #

    public function getDescrizione_eventi() {
      $query = "SELECT `evento`,
                       `sottotitolo`
                FROM   `descrizione_eventi`";
      return $this->getQuery($query);
    }

    #################################
    # gestione utenti (inserimento) #
    #################################

    public function insertUser($nome, $cognome, $email, $password, $data_nascita_utente) {
      $query = "INSERT INTO `utenti` (`nome_utente`,
                                      `cognome_utente`,
                                      `email_utente`,
                                      `password_utente`,
                                      `data_nascita_utente`)
                              VALUES (?, ?, ?, ?, ?)";
      $hidden = password_hash($password, PASSWORD_BCRYPT);
      $types = "sssss";
      $params = [$nome, $cognome, $email, $hidden, $data_nascita_utente];
      return $this->getQuery($query, $types, $params);
    }

    public function updateUser($target, $nome, $cognome, $email, $password, $data_nascita_utente) {
      $safe_target = $this->getUser($target);
      $safe_target = $safe_target[0]['email_utente'];
      $query = "UPDATE `utenti`
                   SET `nome_utente`=?,
                       `cognome_utente`=?,
                       `email_utente`=?,
                       `password_utente`=?,
                       `data_nascita_utente`=?
                 WHERE `email_utente`='".$safe_target."'";
      $hidden = password_hash($password, PASSWORD_BCRYPT);
      $types = "sssss";
      $params = [$nome, $cognome, $email, $hidden, $data_nascita_utente];
      return $this->getQuery($query, $types, $params, false);
    }

    # getters utenti #

    public function getUser($email) {
      $query = "SELECT `nome_utente`,
                       `cognome_utente`,
                       `email_utente`,
                       `password_utente`,
                       `tipo_utente`,
                       `data_nascita_utente`,
                       `data_registrazione_utente`
                FROM   `utenti`
                WHERE  `email_utente`=?";
      $types = "s";
      $params = [$email];
      return $this->getQuery($query, $types, $params);
    }

    ##################################################
    # funzioni generiche e/o usate da altre funzioni #
    ##################################################

    // esegue una query con statement e torna un $output
    private function getQuery($query, $types=null, $params=null, $view=true) {
      $stmt = mysqli_prepare($this->connection, $query);
      if($types && $params) {
        $stmt->bind_param($types, ...$params);
      }
      $stmt->execute();

      if($view) { // query con risultati visualizzabili
        if($result = $stmt->get_result()) {
          while($row = $result->fetch_assoc()) {
            $output[] = $row;
          }
        }
        $stmt->close();
        return $output;
      }
      else {
        if($stmt->affected_rows > 0) $output = true;
        else $output = false;
        $stmt->close();
        return $output;
      }
    }

    // mette i tag di paragrafo ad ogni nuova riga
    public function nl2p($text) {
      return str_replace(array("\r\n", "\r", "\n"), "</p><p>", $text);
    }
  }
?>

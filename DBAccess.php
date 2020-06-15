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

    public function insertTeInfusi($descrizione_immagine, $tipo, $nome, $ingredienti, $descrizione, $preparazione) {
      if(!empty($descrizione_immagine)) {
        $query = "INSERT INTO `te_e_infusi` (`descrizione_immagine_te_e_infusi`,
                                             `tipo_te_e_infusi`,
                                             `nome_te_e_infusi`,
                                             `ingredienti_te_e_infusi`,
                                             `descrizione_te_e_infusi`,
                                             `preparazione_te_e_infusi`)
                                     VALUES (?, ?, ?, ?, ?, ?)";
        $types = "ssssss";
        $params = [$descrizione_immagine, $tipo, $nome, $ingredienti, $descrizione, $preparazione];
      }
      else {
        $query = "INSERT INTO `te_e_infusi` (`tipo_te_e_infusi`,
                                             `nome_te_e_infusi`,
                                             `ingredienti_te_e_infusi`,
                                             `descrizione_te_e_infusi`,
                                             `preparazione_te_e_infusi`)
                                     VALUES (?, ?, ?, ?, ?)";
        $types = "sssss";
        $params = [$tipo, $nome, $ingredienti, $descrizione, $preparazione];
      }
      return $this->getQuery($query, $types, $params, false);
    }

    public function updateTeInfusi($id, $descrizione_immagine, $tipo, $nome, $ingredienti, $descrizione, $preparazione) {
      $safe_id = $this->getSingoloTeInfuso($id);
      $safe_id = $safe_id[0]['id_te_e_infusi'];
      if(!empty($descrizione_immagine)) {
        $query = "UPDATE `te_e_infusi`
                     SET `descrizione_immagine_te_e_infusi`= ?,
                         `tipo_te_e_infusi`= ?,
                         `nome_te_e_infusi`= ?,
                         `ingredienti_te_e_infusi`= ?,
                         `descrizione_te_e_infusi`= ?,
                         `preparazione_te_e_infusi`= ?
                   WHERE `id_te_e_infusi` = '".$safe_id."'";
        $types = "ssssss";
        $params = [$descrizione_immagine, $tipo, $nome, $ingredienti, $descrizione, $preparazione];
      }
      else {
        $query = "UPDATE `te_e_infusi`
                     SET `tipo_te_e_infusi`= ?,
                         `nome_te_e_infusi`= ?,
                         `ingredienti_te_e_infusi`= ?,
                         `descrizione_te_e_infusi`= ?,
                         `preparazione_te_e_infusi`= ?
                   WHERE `id_te_e_infusi` = '".$safe_id."'";
        $types = "sssss";
        $params = [$tipo, $nome, $ingredienti, $descrizione, $preparazione];
      }
      return $this->getQuery($query, $types, $params, false);
    }

    public function deleteTeInfusi($id) {
      $query = "DELETE FROM `te_e_infusi`
                      WHERE `id_te_e_infusi` = ?";
      $types = "i";
      $params = [$id];
      return $this->getQuery($query, $types, $params, false);
    }

    public function getTeInfusi() {
      $query = "SELECT `id_te_e_infusi`,
                       `descrizione_immagine_te_e_infusi`,
                       `nome_te_e_infusi`,
                       `ingredienti_te_e_infusi`,
                       `descrizione_te_e_infusi`,
                       `preparazione_te_e_infusi`
                  FROM `te_e_infusi`";
      return $this->getQuery($query);
    }

    # getters te & infusi #

    public function getId_TeInfusi($nome) {
      $query = "SELECT `id_te_e_infusi`
                  FROM `te_e_infusi`
                 WHERE `nome_te_e_infusi`= ?";
      $types = "s";
      $params = [$nome];
      return $this->getQuery($query, $types, $params, false);
    }

    public function getSingolo_TeInfusi($id) {
      $query = "SELECT `id_te_e_infusi`,
                       `descrizione_immagine_te_e_infusi`,
                       `tipo_te_e_infusi`,
                       `nome_te_e_infusi`,
                       `ingredienti_te_e_infusi`,
                       `descrizione_te_e_infusi`,
                       `preparazione_te_e_infusi`
                FROM   `te_e_infusi`
                WHERE  `id_te_e_infusi`= ?";
      $types = "i";
      $params = [$id];
      return $this->getQuery($query, $types, $params);
    }

    ##################################################
    # gestione eventi (inserimento, visualizzazione) #
    ##################################################

    public function insertEventi($data, $descrizione_immagine, $titolo, $relatori, $mappa, $organizzazione) {
      $query = "INSERT INTO `eventi` (`data_ora_evento`,
                                      `descrizione_immagine_evento`,
                                      `titolo_evento`,
                                      `relatore_evento`,
                                      `mappa_evento`,
                                      `organizzazione_evento`)
                              VALUES (?, ?, ?, ?, ?, ?)";
      $types = "ssssss";
      $params = [$data, $descrizione_immagine, $titolo, $relatori, $mappa, $organizzazione];
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

    public function getId_Eventi($titolo) {
      $query = "SELECT `id_evento`
                  FROM `eventi`
                 WHERE `titolo_evento`= ?";
      $types = "s";
      $params = [$titolo];
      return $this->getQuery($query, $types, $params);
    }

    public function getDescrizione_Eventi() {
      $query = "SELECT `evento`,
                       `sottotitolo`
                FROM   `descrizione_eventi`";
      return $this->getQuery($query);
    }

    # setters eventi #

    public function insertDescrizioneEventi($id, $sottotitolo) {
      $query = "INSERT INTO `descrizione_eventi` (`evento`,
                                                  `sottotitolo`)
                                          VALUES (?, ?)";
      $types = "ss";
      $params = [$id, $sottotitolo];
      return $this->getQuery($query, $types, $params, false);
    }

    public function insertMappaEventi($mappa, $descrizione) {
      $query = "INSERT INTO `mappe_eventi` (`indirizzo_mappe_evento`,
                                            `descrizione_mappe_evento`)
                                    VALUES (?, ?)";
      $types = "ss";
      $params = [$mappa, $descrizione];
      return $this->getQuery($query, $types, $params, false);
    }

    ################################################
    # gestione utenti (inserimento, aggiornamento) #
    ################################################

    public function insertUtenti($nome, $cognome, $email, $password, $data_nascita) {
      $query = "INSERT INTO `utenti` (`nome_utente`,
                                      `cognome_utente`,
                                      `email_utente`,
                                      `password_utente`,
                                      `data_nascita_utente`)
                              VALUES (?, ?, ?, ?, ?)";
      $hidden = password_hash($password, PASSWORD_BCRYPT);
      $types = "sssss";
      $params = [$nome, $cognome, $email, $hidden, $data_nascita];
      return $this->getQuery($query, $types, $params, false);
    }
    // TODO: aggiornare riferimenti ////////////////////////////////////////////////////////////////
    public function updateUtenti($email_target, $nome, $cognome, $email, $password, $data_nascita) {
      $safe_email = $this->getUser($email_target);
      $safe_email = $safe_email[0]['email_utente'];
      $query = "UPDATE `utenti`
                   SET `nome_utente`= ?,
                       `cognome_utente`= ?,
                       `email_utente`= ?,
                       `password_utente`= ?,
                       `data_nascita_utente`= ?
                 WHERE `email_utente`='".$safe_email."'";
      $hidden = password_hash($password, PASSWORD_BCRYPT);
      $types = "sssss";
      $params = [$nome, $cognome, $email, $hidden, $data_nascita];
      return $this->getQuery($query, $types, $params, false);
    }

    # getters utenti #
    // TODO: aggiornare riferimenti ////////////////////////////////////////////////////////////////
    public function getSingolo_Utenti($email) {
      $query = "SELECT `nome_utente`,
                       `cognome_utente`,
                       `email_utente`,
                       `password_utente`,
                       `tipo_utente`,
                       `data_nascita_utente`,
                       `data_registrazione_utente`
                FROM   `utenti`
                WHERE  `email_utente`= ?";
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
          while($row = $result->fetch_assoc()) $output[] = $row;
        }
      }
      else {
        if($stmt->affected_rows > 0) $output = true;
        else $output = false;
      }
      $stmt->close();
      return $output;
    }

    // mette i tag di paragrafo ad ogni nuova riga
    public function nl2p($text) {
      return str_replace(array("\r\n", "\r", "\n"), "</p><p>", $text);
    }
  }
?>

<?php
  class DBAccess{
    const HOST_DB = 'localhost';
    const USER_NAME = 'erboristeriatest';
    const PASSWORD = '';
    const DB_NAME = 'my_erboristeriatest';

    public $connection = null;

    public function openConnection(){
      $this->connection = mysqli_connect(static::HOST_DB,static::USER_NAME, static::PASSWORD, static::DB_NAME);
      return ($this->connection ? true : false);
    }

    public function InsertTeInfusi($descImg, $tipo, $nome, $ingre, $descr, $prepa){
      if($descImg != ""){
        $query = "INSERT INTO `te_e_infusi` (`descrizione_immagine_te_e_infusi`,`tipo_te_e_infusi`, `nome_te_e_infusi`, `ingredienti_te_e_infusi`, `descrizione_te_e_infusi`, `preparazione_te_e_infusi`) VALUES ('".$descImg."','".$tipo."','".$nome."','".$ingre."','".$descr."','".$prepa."')";
      }
      else{
        $query = "INSERT INTO `te_e_infusi` (`tipo_te_e_infusi`, `nome_te_e_infusi`, `ingredienti_te_e_infusi`, `descrizione_te_e_infusi`, `preparazione_te_e_infusi`) VALUES ('".$tipo."','".$nome."','".$ingre."','".$descr."','".$prepa."')";
      }
      return (mysqli_query($this->connection, $query) ? true : false);
    }

    public function deleteTeInfusi($name){
      $query = "DELETE FROM `te_e_infusi` WHERE `nome_te_e_infusi` = '".$name."'";
      return (mysqli_query($this->connection, $query) ? true : false);
    }

    public function getTeInfusi(){
      $query = "SELECT `id_te_e_infusi`, `descrizione_immagine_te_e_infusi`, `nome_te_e_infusi`, `ingredienti_te_e_infusi`, `descrizione_te_e_infusi`, `preparazione_te_e_infusi` FROM `te_e_infusi`";
      if($result = mysqli_query($this->connection, $query)){
        while($row = mysqli_fetch_assoc($result)){
          $output[] = $row;
        }
      }
      return $output;
    }

    public function getEventi(){
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
                FROM   `eventi`,
                       `mappe_eventi`
                WHERE  `mappa_evento` = `indirizzo_mappe_evento`";

      return $this->getQuery($query);
    }

    public function getDescrizione_eventi(){
      $query = "SELECT `evento`,
                       `sottotitolo`
                FROM   `descrizione_eventi`";

      return $this->getQuery($query);
    }

    private function getQuery($query){
      if($result = mysqli_query($this->connection, $query)){
        while($row = mysqli_fetch_assoc($result)){
          $output[] = $row;
        }
      }
      return $output;
    }

    public function getId($name){
      $result = "errore";
      $query = "SELECT `id_te_e_infusi` FROM `te_e_infusi` WHERE `nome_te_e_infusi`= '".$name."'";
      if($res = mysqli_query($this->connection, $query)){
        $row = mysqli_fetch_array($res);
        $result = $row['id_te_e_infusi'];
      }
      return $result;
    }

    public function closeConnection(){
      mysqli_close($this->connection);
    }

  }
?>

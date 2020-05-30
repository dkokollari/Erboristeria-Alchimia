<?php
  class DBAccess{
    const HOST_DB = 'localhost';
    const USER_NAME = 'erboristeriatest';
    const PASSWORD = '';
    const DB_NAME = 'my_erboristeriatest';

    public $connection = null;

    /*
      gestione connessione al database
    */
    public function openConnection(){
      $this->connection = mysqli_connect(static::HOST_DB, static::USER_NAME, static::PASSWORD, static::DB_NAME);
      return ($this->connection ? true : false);
    }

    public function closeConnection(){
      mysqli_close($this->connection);
    }

    /*
      gestione te e infusi (inserimento, aggiornamento, rimozione, visualizzazione)
    */
    public function InsertTeInfusi($descImg, $tipo, $nome, $ingre, $descr, $prepa){
      if($descImg != ""){
        $query = "INSERT INTO `te_e_infusi` (`descrizione_immagine_te_e_infusi`, `tipo_te_e_infusi`, `nome_te_e_infusi`, `ingredienti_te_e_infusi`, `descrizione_te_e_infusi`, `preparazione_te_e_infusi`) VALUES ('".$descImg."','".$tipo."','".$nome."','".$ingre."','".$descr."','".$prepa."')";
      }
      else{
        $query = "INSERT INTO `te_e_infusi` (`tipo_te_e_infusi`, `nome_te_e_infusi`, `ingredienti_te_e_infusi`, `descrizione_te_e_infusi`, `preparazione_te_e_infusi`) VALUES ('".$tipo."','".$nome."','".$ingre."','".$descr."','".$prepa."')";
      }
      return (mysqli_query($this->connection, $query) ? true : false);
    }

    public function updateTeInfusi($id, $nome, $tipo, $ingre, $desc, $prepa, $descImg){
      $query= "UPDATE `te_e_infusi` SET `descrizione_immagine_te_e_infusi`= '".$descImg."', `tipo_te_e_infusi`='".$tipo."', `nome_te_e_infusi`='".$nome."', `ingredienti_te_e_infusi`='".$ingre."', `descrizione_te_e_infusi`='".$desc."', `preparazione_te_e_infusi`='".$prepa."' WHERE `id_te_e_infusi` = '".$id."'";
      return (($res = mysqli_query($this->connection, $query)) ? true : false);
    }

    public function deleteTeInfusi_by_id($id){
      $query="DELETE FROM `te_e_infusi` WHERE `id_te_e_infusi` = '".$id."'";
      return (mysqli_query($this->connection, $query) ? true : false);
    }

    public function deleteTeInfusi_by_name($name){
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

    public function getTeInfusiv1(){
      $query = "SELECT * FROM te_e_infusi";
      $queryResult = mysqli_query($this->connection,$query);

      if(mysqli_num_rows($queryResult) == 0){
        return null;
      }
      else{
        $result = array();
        while($row = mysqli_fetch_assoc($queryResult)){
          $arrayTeInfuso = array(
            'Id' => $row['id_te_e_infusi'],
            'Descrizione_img' => $row['descrizione_immagine_te_e_infusi'],
            'Tipo' => $row['tipo_te_e_infusi'],
            'Nome' => $row['nome_te_e_infusi'],
            'Ingredienti' => $row['ingredienti_te_e_infusi'],
            'Descrizione' => $row['descrizione_te_e_infusi'],
            'Preparazione' => $row['preparazione_te_e_infusi'],
          );
          array_push($result,$arrayTeInfuso);
        }
        return $result;
      }
    }

    /*
      getter informazioni di te e infusi
    */
    public function getId($name){
      $result = "errore";
      $query = "SELECT `id_te_e_infusi` FROM `te_e_infusi` WHERE `nome_te_e_infusi`= '".$name."'";
      if($res = mysqli_query($this->connection, $query)){
        $row = mysqli_fetch_array($res);
        $result = $row['id_te_e_infusi'];
      }
      return $result;
    }

    public function getSingoloTeInfuso($id){
      $query="SELECT * FROM te_e_infusi WHERE id_te_e_infusi= '".$id."'";
      $queryResult = mysqli_query($this->connection,$query);

      if(mysqli_num_rows($queryResult) == 0){
       return null;
      }
      else{
        $row = mysqli_fetch_assoc($queryResult);
        $result = array(
          'Id' => $row['id_te_e_infusi'],
          'desc_img' => $row['descrizione_immagine_te_e_infusi'],
          'Tipo' => $row['tipo_te_e_infusi'],
          'Nome' => $row['nome_te_e_infusi'],
          'Ingredienti' => $row['ingredienti_te_e_infusi'],
          'Descrizione' => $row['descrizione_te_e_infusi'],
          'Preparazione' => $row['preparazione_te_e_infusi'],
        );
        return $result;
      }
    }

    /* esegue una query e torna un $output */
    private function getQuery($query, $types=null, $params=null){
      $stmt = $this->prepare($query);
      if($types && $params){
        $stmt->bind_param($types, ...$params);
      }
      $stmt->execute();

      if($result = $stmt->get_result()){
        while($row =  $result->fetch_assoc()){
          $output[] = $row;
        }
      }
      $stmt->close();
      return $output;
    }

  } //fine classe DBAccess
?>

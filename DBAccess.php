<?php
class DBAccess{
  const HOST_DB = 'localhost';
  const USER_NAME = 'erboristeriatest';
  const PASSWORD = '';
  const DB_NAME = 'my_erboristeriatest';

  public $connection = null;

  public function openConnection() {
    $this->connection = mysqli_connect(static::HOST_DB,static::USER_NAME, static::PASSWORD, static::DB_NAME);
    if($this->connection){
      return true;
    }
    return false;
  }

  public function InsertTeInfusi($descImg,$tipo,$nome,$ingre,$descr,$prepa) {

    if($descImg != ""){
      $query = "INSERT INTO `te_e_infusi` (`descrizione_immagine_te_e_infusi`,`tipo_te_e_infusi`, `nome_te_e_infusi`, `ingredienti_te_e_infusi`, `descrizione_te_e_infusi`, `preparazione_te_e_infusi`) VALUES ('".$descImg."','".$tipo."','".$nome."','".$ingre."','".$descr."','".$prepa."')";
    }
    else {
      $query = "INSERT INTO `te_e_infusi` (`tipo_te_e_infusi`, `nome_te_e_infusi`, `ingredienti_te_e_infusi`, `descrizione_te_e_infusi`, `preparazione_te_e_infusi`) VALUES ('".$tipo."','".$nome."','".$ingre."','".$descr."','".$prepa."')";
    }

    if($res = mysqli_query($this->connection,$query)){
      return true;
    }
    return false;

  }

  public function deleteTeInfusi($name){
    $query="DELETE FROM `te_e_infusi` WHERE `nome_te_e_infusi` = '".$name."'";
    if($res = mysqli_query($this->connection,$query)){
      return true;
    }
    return false;
  }

  public function getId($name){
    $result="";
    $query="SELECT id_te_e_infusi FROM te_e_infusi WHERE nome_te_e_infusi= '".$name."'";
    if($res = mysqli_query($this->connection,$query)){
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

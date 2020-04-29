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
    $result="errore";
    $query="SELECT id_te_e_infusi FROM te_e_infusi WHERE nome_te_e_infusi= '".$name."'";
    if($res = mysqli_query($this->connection,$query)){
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
    }else{
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

  public function closeConnection(){
    mysqli_close($this->connection);
  }

  public function getTeInfusi(){

    $query = "SELECT * FROM te_e_infusi";
    $queryResult = mysqli_query($this->connection,$query);

   if(mysqli_num_rows($queryResult) == 0){
      return null;
    } else {
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

  public function updateTeInfusi($id,$nome, $tipo,$ingre, $desc, $prepa, $descImg){
    $query= "UPDATE `te_e_infusi` SET `descrizione_immagine_te_e_infusi`= '".$descImg."',`tipo_te_e_infusi`='".$tipo."',`nome_te_e_infusi`='".$nome."',`ingredienti_te_e_infusi`='".$ingre."',`descrizione_te_e_infusi`='".$desc."',`preparazione_te_e_infusi`='".$prepa."' WHERE `id_te_e_infusi` = '".$id."'";
    if($res = mysqli_query($this->connection,$query)){
      return true;
    }
    return false;
  }


}

 ?>

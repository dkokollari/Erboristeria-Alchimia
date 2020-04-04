<?php
class DBAcess{
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

  public function getTeInfusi() {}
      

}

 ?>

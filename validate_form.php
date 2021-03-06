<?php
  class Validate_form{
    public function check_pwd($password){
      // lunghezza minima 8 e massima 100 caratteri, almeno 1 lettera ed 1 numero
      return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,100}$/', $password);
    }

    public function check_str($stringa){
      // lunghezza minima 3 e massima 100 caratteri
      return preg_match('/^([A-Z]|[a-z]){3,100}$/', $stringa);
    }

    public function is_empty($params=null){
      foreach ($params as $value){
        if(empty($value)) return true;
      }
      // in caso sia stato passato $params non-array oppure nessun parametro
      return empty($params);
    }
  }
?>

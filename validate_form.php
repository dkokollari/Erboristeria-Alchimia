<?php
  class Validate_form{
    /* lunghezza minima 8 e massima 12 caratteri, almeno 1 lettera ed 1 numero */
    public function check_pwd($password){
      return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,12}$/', $password);
    }

    /* lunghezza minima 3 e massima 200 caratteri */
    public function check_str($stringa){
      return preg_match('/^([A-Z]|[a-z])[a-z]{2,255}$/', $stringa);
    }

    public function is_empty($params=null){
      foreach ($params as $value){
        if(empty($value)) return true;
      }
      /* in caso non sia passato alcun parametro */
      if(empty($params)){
        return true;
      }
      return false;
    }
  }
?>

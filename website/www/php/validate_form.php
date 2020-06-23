<?php
  class Validate_form {
    // lunghezza minima 8 caratteri e massima 100, almeno 1 lettera ed 1 numero
    public static function check_pwd($password) {
      return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,100}$/', $password);
    }
    // lunghezza minima 3 caratteri e massima 100
    public static function check_str($stringa) {
      return preg_match('/^([A-Z]|[a-z]){3,100}$/', $stringa);
    }

    public static function is_empty($params=null) {
      foreach ($params as $value)
        if(empty($value))
          return true;
      return empty($params);
    }

  }
?>

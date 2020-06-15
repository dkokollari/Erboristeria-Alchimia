<?php
  class control_input {
    // previene SQL injection
    public static function control($input) {
      return trim(htmlentities(strip_tags($input)));
    }
    // controlla se nome ha almeno 5 caratteri e massimo 50, contiene i caratteri a-z A-Z 0-9 - _
    public static function name_control($input) {
      if(strlen($input) < 5 || strlen($input) > 50) {
        return false;
      }
      $input = control_input::control($input);
      if(preg_match('/[a-z_]/i', $input)) {
          return $input;
      }
      return false;
    }
    // controlla se relatori ha almeno 5 caratteri e massimo 600, contiene i caratteri a-z A-Z 0-9 - _
    public static function rel_control($input) {
      if(strlen($input) < 5 || strlen($input) > 600) {
        return false;
      }
      $input = control_input::control($input);
      if(preg_match('/[a-z_]/i', $input)) {
        return $input;
      }
      return false;
    }
    // controlla se descrizione ha almeno 20 caratteri e massimo 500
    public static function description_control($input) {
      if(strlen($input) < 20  ||  strlen($input) > 500) {
        return false;
      }
      $input = control_input::control($input);
      return $input;
    }
    // controlla se descrizione_sottotitolo ha almeno 5 caratteri e massimo 100
    public static function desctitoli_control($input) {
      if(strlen($input) < 5  ||  strlen($input) > 100) {
        return false;
      }
      $input = control_input::control($input);
      return $input;
    }
    // controlla se indirizzo ha almeno 3 caratteri e massimo 50, contiene i caratteri a-z A-Z 0-9 - _
    public static function ind_control($input) {
      if(strlen($input) < 5 || strlen($input) > 50) {
        return false;
      }
      $input = control_input::control($input);
      if(preg_match('/[a-z_\-0-9]/i', $input)) {
        return $input;
      }
      return false;
    }

  }
?>

<?php
// CLASSE PER IL CONTROLLO DI INPUT

class control_input{

  //metodo per prevenire sql injection
  public static function control($input)
  {
      return trim(htmlentities(strip_tags($input)));
  }

  //metodo che controlla il nome
  public static function name_control($input)
  {
      if (strlen($input) < 5 || strlen($input) > 50) {
          return false;
      }
      $input = control_input::control($input);
      if (preg_match('/[a-z_]/i', $input)) { //contiene i caratteri a-z A-Z 0-9 - _
          return $input;
      }
      return false;
  }

  //metodo che controlla il campo descrzione
  public static function description_control($input)
  {
      if (strlen($input) < 20  ||  strlen($input) > 500) {
        return false;
      }
      $input = control_input::control($input);
      return $input;
  }

  public static function desctitoli_control($input)
  {
      if (strlen($input) < 5  ||  strlen($input) > 100) {
        return false;
      }
      $input = control_input::control($input);
      return $input;
  }

  //metodo che controlla l'indirizzo
  public static function ind_control($input)
  {
      if (strlen($input) < 5 || strlen($input) > 50) {
          return false;
      }
      $input = control_input::control($input);
      if (preg_match('/[a-z_\-0-9]/i', $input)) { //contiene i caratteri a-z A-Z 0-9 - _
          return $input;
      }
      return false;
  }

}

 ?>

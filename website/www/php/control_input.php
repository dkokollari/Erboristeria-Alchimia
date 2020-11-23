<?php
class control_input
{

  public static function control($input)
  {
    return trim(htmlentities(strip_tags($input)));
  }
  // controlla se nome ha almeno 5 caratteri e massimo 50
  public static function name_control($input)
  {
    $input = control_input::control($input);
    if (!preg_match('/^[a-zA-Z_-]{5,50}$/', $input))
    {
      return false;
    }
    return true;
  }
  // controlla se relatori ha almeno 5 caratteri e massimo 600
  public static function rel_control($input)
  {
    $input = control_input::control($input);
    if (!preg_match('/^[a-zA-Z_-,:]{5,500}$/', $input))
    {
      return false;
    }
    return true;
  }
  // controlla text area te_e_infusi
  public static function text_control($input)
  {
    $input = control_input::control($input);
    if (!preg_match('/^[a-zA-Z0-9_-,.:;?!]{5,500}$/', $input))
    {
      return false;
    }
    return true;
  }

  // controlla se descrizione_sottotitolo ha almeno 5 caratteri e massimo 100
  public static function desctitoli_control($input)
  {
    $input = control_input::control($input);
    if (!preg_match('/^[a-zA-Z_-?!;:,""]{5,100}$/', $input))
    {
      return false;
    }
    return true;
  }
  // controlla se indirizzo ha almeno 3 caratteri e massimo 50, contiene i caratteri a-z A-Z 0-9 - _
  public static function ind_control($input)
  {
    $input = control_input::control($input);
    if (!preg_match('/^[a-zA-Z0-9,-]{5,50}$/', $input))
    {
      return false;
    }
    return true;
  }

}
?>

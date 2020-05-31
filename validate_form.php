<?php
  /* lunghezza minima 8 e massima 12 caratteri, almeno 1 lettera ed 1 numero */
  function check_pwd($password){
          if(preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,12}$/',$password)==1){
              return true;
          }
          return false;
      }

  /* lunghezza minima 3 e massima 255 caratteri */
  function check_stringa($stringa){
          if(preg_match('^([A-Z] | [a-z])[a-z]{3,255}',$stringa)==1){
              return true;
          }
          return false;
  }
?>

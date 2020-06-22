<?php
  class Utilities {

    public static function getNumericValue_GET($index) {
      return ((isset($_GET[$index]) && is_numeric($_GET[$index]) && (int)($_GET[$index]) > 0)
              ? (int)$_GET[$index]
              : 1);
    }

    public static function getNumericValue_POST($index) {
      return ((isset($_POST[$index]) && is_numeric($_POST[$index]) && (int)($_POST[$index]) > 0)
              ? (int)$_POST[$index]
              : 1);
    }

  }
?>

<?php
class Utilities {
  public function getNumericValue($index) {
    return isset($_GET[$index]) ? && is_numeric($_GET[$index]) && (int)($_GET[$index]) > 0 ? (int)$_GET[$index] : 1;
  }
}
?>

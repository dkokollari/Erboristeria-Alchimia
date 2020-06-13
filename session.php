<?php
    session_start();

    if(!isset($_SESSION['auth'])) $_SESSION['auth'] = false;
    if(!isset($_SESSION['tipo_utente'])) $_SESSION['tipo_utente'] = 'Visitatore';
?>

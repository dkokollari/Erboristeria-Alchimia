<?php

    session_start();

    if(!isset($_SESSION['auth'])){
        $_SESSION['auth']=false;
    }

    if(!isset($_SESSION['utente'])){
        $_SESSION['utente']='Visitatore';
    }


?>

<?php
session_start();

if (!isset($_SESSION['auth'])) $_SESSION['auth'] = false;
if (!isset($_SESSION['tipo_utente'])) $_SESSION['tipo_utente'] = "Visitatore";
if (!isset($_SESSION['nome_utente'])) $_SESSION['nome_utente'] = "";
if (!isset($_SESSION['cognome_utente'])) $_SESSION['cognome_utente'] = "";
if (!isset($_SESSION['email_utente'])) $_SESSION['email_utente'] = "";
if (!isset($_SESSION['password_utente'])) $_SESSION['password_utente'] = "";
if (!isset($_SESSION['tipo_utente'])) $_SESSION['tipo_utente'] = "";
if (!isset($_SESSION['data_nascita_utente'])) $_SESSION['data_nascita_utente'] = "";
?>

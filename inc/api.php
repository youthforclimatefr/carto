<?php

require_once 'utils/ApiAnswers.php';
require_once 'rubriques/Rubriques.php';

session_start();

// DANGEREUX à enlever en prod !
if ($_SERVER['HTTP_HOST'] == 'localhost:7770') {
    ini_set("display_errors", "1");
    error_reporting(E_ALL);

    if (array_key_exists('setUserID', $_REQUEST)) {
        $_SESSION['idPersonne'] = $_REQUEST['setUserID'];
        success('Vous êtes enregistré en tant qu\'utilisateur ' . $_REQUEST['setUserID']);
    }
}

// Configuration de l'environnement
$GLOBALS['conf'] = parse_ini_file("../config.ini");

new Rubriques();
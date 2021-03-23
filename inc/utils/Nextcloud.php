<?php

define('NC_LOGIN', 'https://cloud.becauseofprog.fr/index.php/login/v2');

/**
 * Get a Nextcloud login URL
 */
function getLoginURL(): string {
    session_start();

    $curl = curl_init(NC_LOGIN);
    curl_setopt($curl, CURLOPT_URL, NC_LOGIN);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'USER_AGENT: Cartographie',
        'ACCEPT_LANGUAGE: fr'
    ));

    $resp = json_decode(curl_exec($curl), true);
    
    curl_close($curl);

    $_SESSION['tmp_token'] = $resp['poll']['token'];
    $_SESSION['tmp_endpoint'] = $resp['poll']['endpoint'];

    return $resp['login'];
}


/**
 * Connect the user with the fields 
 */
function connectUser() {
    session_start();

    $curl = curl_init($_SESSION['tmp_endpoint']);
    curl_setopt($curl, CURLOPT_URL, $_SESSION['tmp_endpoint']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "token=" . $_SESSION['tmp_token']); 

    try {
        $resp = json_decode(curl_exec($curl));
    
        $_SESSION['server'] = $resp['server'];
        $_SESSION['loginName'] = $resp['loginName'];
        $_SESSION['appPassword'] = $resp['appPassword'];
    } catch(Exception $e) {
        bye();
    }

    curl_close($curl);
}
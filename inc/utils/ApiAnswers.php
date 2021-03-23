<?php

/**
 * Redirige un utilisateur qui n'a pas les permissions
 * à l'URL donnée ou à l'accueil du site par défaut.
 * @param string adresse web, facultative
 */
function bye($url = '') {
    header('Status: 302 Moved');
    if (empty($url)) header("Location: /");
    else header("Location: " . $url);
    exit();
}

/**
 * Réponse Ajax signifiant le succès de la requête
 */
function success($content = null)
{
    header('Content-Type: application/json');
    die(
        json_encode([
            'success' => true,
            'content' => $content
        ], JSON_PRETTY_PRINT)
    );
}

/**
 * Réponse Ajax signifiant l'échec de la requête
 */
function error(string $message = '')
{
    header('Content-Type: application/json');
    http_response_code(400);
    die(
        json_encode([
            'success' => false,
            'message' => $message
        ])
    );
}
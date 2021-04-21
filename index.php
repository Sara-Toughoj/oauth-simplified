<?php

function apiRequest($url, $post = FALSE, $headers = array())
{

    // Fill these out with the values from GitHub
    $githubClientID = 'ab1762ce2a0c5364efda';
    $githubClientSecret = 'be3d808d0156806701d09cae9a4b59cda3aae003';

// This is the URL we'll send the user to first
// to get their authorization
    $authorizeURL = 'https://github.com/login/oauth/authorize';

// This is the endpoint we'll request an access token from
    $tokenURL = 'https://github.com/login/oauth/access_token';

// This is the GitHub base URL for API requests
    $apiURLBase = 'https://api.github.com/';

// The URL for this script, used as the redirect URL
    $baseURL = 'https://' . $_SERVER['SERVER_NAME']
        . $_SERVER['PHP_SELF'];

// Start a session so we have a place to
// store things between redirects
    session_start();


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    if ($post)
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    $headers = [
        'Accept: application/vnd.github.v3+json, application/json',
        'User-Agent: http://127.0.0.1:8001/'
    ];

    if (isset($_SESSION['access_token']))
        $headers[] = 'Authorization: Bearer ' . $_SESSION['access_token'];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response, true);
}


// If there is an access token in the session
// the user is already logged in
if (!isset($_GET['action'])) {
    if (!empty($_SESSION['access_token'])) {
        echo '<h3>Logged In</h3>';
        echo '<p><a href="?action=repos">View Repos</a></p>';
        echo '<p><a href="?action=logout">Log Out</a></p>';
    } else {
        echo '<h3>Not logged in</h3>';
        echo '<p><a href="?action=login">Log In</a></p>';
    }
    die();
}

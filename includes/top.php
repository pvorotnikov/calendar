<?php
session_start();

// handle special home page case
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = $_GET['page'];
}
else {
    $page = 'home';
}

// check if page exists
if (!file_exists('pages/' . $page . '.php')) {
    $page = '404';
}

// validate login
switch ($page) {

    // leave some pages unprotected
    case 'home':
        if (isset($_SESSION['logged']) && $_SESSION['logged']) {
            header('Location: /?page=calendar');
        }
        break;

    case 'signup':
    case '404':
        break;

    // protect all other pages
    default:
        // check if user has logged in
        if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
            header('Location: /');
        }
        break;
}



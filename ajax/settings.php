<?php
require_once('../config.php');
require_once('../includes/db.php');
require_once('../includes/functions.php');
$db = new Db(Config::DB_HOST, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);

session_start();
header('Content-type: application/json');

/**
 * Protect this script
 * Respond with 401
 */
if (!isset($_SESSION['logged']) || !$_SESSION['logged']) {
    $response = array('status' => 'error', 'error' => 'FORBIDDEN', 'errorMessage' => '');
    http_response_code(401);
    echo json_encode($response);
    exit();
}

switch ($_GET['do']) {

    /**
     * Peform settings update
     */
    case 'update':

        $password = md5($_POST['password']);
        $password2 = md5($_POST['passwordrepeat']);
        $email = trim($_POST['email']);

        $query = NULL;

        $updatePassword = (!empty($_POST['password']) && !empty($_POST['passwordrepeat']));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Enter a valid email');
        } else if ($updatePassword && $password !== $password2) {
            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Passwords don\'t match');
        } else {

            // sanitize input
            $email = $db->conn->real_escape_string($email);

            if ($updatePassword) {
                $query = "UPDATE user SET password='$password', email='$email'";
            } else {
                $query = "UPDATE user SET email='$email'";
            }
        }

        if ($query) {
            if ($result = $db->conn->query($query)) {
                $response = array('status' => 'ok', 'data' => '');
            } else {
                $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'DB Error');
            }
        }

        echo json_encode($response);
        break;

    /**
     * Default request handler.
     * Respond with 400
     */
    default:
        http_response_code(400);
        $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Bad request');
        echo json_encode($response);
        break;
}

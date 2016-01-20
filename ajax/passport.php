<?php
require_once('../config.php');
require_once('../includes/db.php');
require_once('../includes/functions.php');
$db = new Db(Config::DB_HOST, Config::DB_NAME, Config::DB_USER, Config::DB_PASS);

session_start();
header('Content-type: application/json');

/**
 * Leave this script unprotected
 */

switch ($_GET['do']) {

    /**
     * Perform login
     */
    case 'login':

        $username = stripslashes(trim($_POST['username']));
        $password = md5($_POST['password']);
        $userid = NULL;
        $susccess = false;

        // query the database
        $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        if ($result = $db->conn->query($query)) {
            if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                $userid = $row['id'];
                $susccess = true;
            }
            $result->close();
        }

        // creusernamedentials are valid
        if ($susccess) {
            $_SESSION['logged'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['userid'] = $userid;

            $response = array('status' => 'ok', 'data' => '');
            echo json_encode($response);
        // credentials are invalid
        } else {
            $response = array('status' => 'error',
                'error' => 'BAD_REQUEST',
                'errorMessage' => 'The username or password are invalid');
            echo json_encode($response);
        }
        break;

    /**
     * Perform signup
     */
    case 'signup':

        $username = stripslashes(trim($_POST['username']));
        $password = md5($_POST['password']);
        $password2 = md5($_POST['passwordrepeat']);
        $email = trim($_POST['email']);

        // verify required fields
        if (empty($username) || empty($password) || empty($password2) || empty($email)) {
            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Fill all required fields');

        // verify password
        } else if ($password !== $password2) {
            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Passwords don\'t match');

        // validate email
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Enter a valid email');

        // execute query
        } else {

            // sanitize input
            $username = $db->conn->real_escape_string($username);

            if ($db->conn->query("INSERT INTO user (username, password, email, createdAt) VALUES ('$username', '$password', '$email', NOW())")) {

                $_SESSION['logged'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['userid'] = $db->conn->insert_id;

                $response = array('status' => 'ok', 'data' => '');
            } else {
                $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'User already exists');
            }
        }
        echo json_encode($response);
        break;

    /**
     * Perform logout
     */
    case 'logout':
        session_destroy();
        $response = array('status' => 'ok', 'data' => '');
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

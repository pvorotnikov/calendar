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
     * Add or edit an event that belongs to the
     * currently authenticated user.
     */
    case 'add':
    case 'update':

        // get input
        $start = trim($_POST['startTime']);
        $end = trim($_POST['endTime']);
        if (empty($end)) {
            $end = $start;
        }
        $name = trim($_POST['eventName']);
        $description = trim($_POST['eventDescription']);

        // validate input
        if (empty($start) || empty($name)) {

            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Fill required fields');

        } else if (!filter_var($start, FILTER_VALIDATE_INT) || !filter_var($end, FILTER_VALIDATE_INT)) {

            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'Start and end must be numbers');

        } else if ($start > $end) {

            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'End must be later than start');

        } else {

            // convert start and end to DATETIME from JS miliseconds
            $start = date("Y-m-d H:i:s", $start/1000);
            $end = date("Y-m-d H:i:s", $end/1000);

            // sanitize input
            $name = $db->conn->real_escape_string($name);
            $description = $db->conn->real_escape_string($description);

            // form query depending on the action
            if ('add' == $_GET['do']) {

                // create record
                $query = "INSERT INTO event (userId, name, description, start, end, flags, createdAt)
                      VALUES (".$_SESSION['userid'].", '$name', '$description', '$start', '$end', 0, NOW())";

            } else if ('update' == $_GET['do']) {

                // get event id
                $id = $_POST['id'];

                // edit record
                $query = "UPDATE event SET name='$name', description='$description', start='$start', end='$end'
                      WHERE id=$id AND userId=".$_SESSION['userid'];

            }

            // execute query
            if ($db->conn->query($query)) {
                $response = array('status' => 'ok', 'data' => '');
            } else {
                $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => $db->conn->error);
            }
        }

        echo json_encode($response);
        break;

    /**
     * Delete event.
     * An event can be only deleted if it belongs
     * to the currently authenticated user.
     */
    case 'delete':

        $id = $_POST['id'];
        $query = "DELETE FROM event WHERE id = $id AND userId = " . $_SESSION['userid'];
        if ($db->conn->query($query)) {
            $response = array('status' => 'ok', 'data' => '');
        } else {
            $response = array('status' => 'error', 'error' => 'BAD_REQUEST', 'errorMessage' => 'DB Error');
        }

        echo json_encode($response);
        break;

    /**
     * Get a list of events to be shown in the calendar.
     * This is called automatically by the calendar component
     * and receives the events timespan.
     */
    case 'events':

        $from = $_GET['from'];
        $to = $_GET['to'];

        if (!filter_var($from, FILTER_VALIDATE_INT) || !filter_var($to, FILTER_VALIDATE_INT)) {
            $response = array('success' => 0, 'error' => 'BAD_REQUEST');
        } else {

            // convert from and to to DATETIME from JS miliseconds
            $from = date("Y-m-d H:i:s", $from/1000);
            $to = date("Y-m-d H:i:s", $to/1000);

            // execute query
            $query = "SELECT * FROM event WHERE userId=" . $_SESSION['userid'] . " AND start >= '$from' AND end <= '$to'";
            if ($result = $db->conn->query($query)) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($data, array(
                        'id' => $row['id'],
                        'title' => $row['name'],
                        'url' => '/?page=event&id=' . $row['id'],
                        'start' => (string) (strtotime($row['start']) * 1000),
                        'end' => (string) (strtotime($row['end']) * 1000)
                    ));
                }
                $response = array('success' => 1, 'result' => $data);
                $result->close();
            } else {
                $response = array('success' => 0, 'error' => 'INTERNAL_SERVER_ERROR');
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

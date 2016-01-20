<?php
class Db {

    public $conn;

    function __construct($host, $name, $user, $pass) {
        $this->conn = new mysqli($host, $user, $pass, $name);

        if ($this->conn->connect_error) {
            die("DB Connection failed");
        }
    }

}

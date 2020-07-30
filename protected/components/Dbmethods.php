<?php
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
// This Class Handles DB Queries
class Dbmethods{
    // production db connection
    public function KarfDB()
    {
        $servername = "192.168.0.4";
        $username = "root";
        $password = "Pambazuka08";
        $dbname = "karftracker";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }else{
            return $conn;
        }
    }
}
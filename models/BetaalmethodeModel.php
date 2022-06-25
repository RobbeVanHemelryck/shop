<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class BetaalmethodeModel {
    static function getAllBetaalmethoden() {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM betaalmethoden");
        $betaalmethoden = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $naam = $row[1];
            $kosten_geld = $row[2];
            $kosten_procent = $row[3];
            $img_path = $row[4];

            $betaalmethode = new BetaalmethodeEntity($id, $naam, $kosten_geld, $kosten_procent, $img_path);

            array_push($betaalmethoden, $betaalmethode);
        }

        mysqli_close($mysqli);
        return $betaalmethoden;
    }
    
    static function getBetaalmethode($id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM betaalmethoden WHERE id='$id'");
        $betaalmethode = null;

        if (!$result) {
            /*printf("Error: %s\n", mysqli_error($mysqli));*/
            mysqli_close($mysqli);
            return false;
        }
        if($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $naam = $row[1];
            $kosten_geld = $row[2];
            $kosten_procent = $row[3];
            $img_path = $row[4];

            $betaalmethode = new BetaalmethodeEntity($id, $naam, $kosten_geld, $kosten_procent, $img_path);
        }

        mysqli_close($mysqli);
        return $betaalmethode;
    }
    static function addBetaalmethode($betaalmethode){
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("INSERT INTO betaalmethoden (id, naam, kosten_geld, kosten_procent, img_path) VALUES ('$betaalmethode->id', '$betaalmethode->naam', '$betaalmethode->kosten_geld', '$betaalmethode->kosten_procent', '$betaalmethode->img_path')");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }

        $id = mysqli_insert_id($mysqli);

        mysqli_close($mysqli);
        return $id;
    }
}

?>

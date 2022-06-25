<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class LevermethodeModel {
    static function getAllLevermethoden() {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM levermethoden");
        $levermethoden = [];

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
            $duur = $row[5];

            $levermethode = new LevermethodeEntity($id, $naam, $kosten_geld, $kosten_procent, $img_path, $duur);

            array_push($levermethoden, $levermethode);
        }

        mysqli_close($mysqli);
        return $levermethoden;
    }
    
    static function getLevermethode($id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result = $mysqli->query("SELECT * FROM levermethoden WHERE id='$id'");
        $levermethode = null;

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
            $duur = $row[5];

            $levermethode = new LevermethodeEntity($id, $naam, $kosten_geld, $kosten_procent, $img_path, $duur);
        }

        mysqli_close($mysqli);
        return $levermethode;
    }
    static function addLevermethode($levermethode){
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("INSERT INTO levermethoden (id, naam, kosten_geld, kosten_procent, img_path, duur) VALUES ('$levermethode->id', '$levermethode->naam', '$levermethode->kosten_geld', '$levermethode->kosten_procent', '$levermethode->img_path', '$levermethode->duur')");

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

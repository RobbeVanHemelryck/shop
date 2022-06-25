<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

class CategorieModel {
    static function getAllCategorien() {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM categorie WHERE active=1 ORDER BY naam");
        $categorien = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $naam = $row[1];
            $active = $row[2];

            $categorie = new CategorieEntity($id, $naam, $active);

            array_push($categorien, $categorie);
        }

        mysqli_close($mysqli);
        return $categorien;
    }

    static function getCategorie($id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM categorie WHERE id='$id'");
        $categorie = null;

        if (!$result) {
            //printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            return false;
        }
        if($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $naam = $row[1];
            $active = $row[2];

            $categorie = new CategorieEntity($id, $naam, $active);
        }

        mysqli_close($mysqli);
        return $categorie;
    }

    static function getCategorieByNaam($naam) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM categorie WHERE naam='$naam' AND active=1");
        $categorie = null;

        if (!$result) {
            //printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            return false;
        }
        if($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $naam = $row[1];
            $active = $row[2];

            $categorie = new CategorieEntity($id, $naam, $active);
        }

        mysqli_close($mysqli);
        return $categorie;
    }

    static function addCategorie($categorie){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $naam = addslashes($categorie->naam);
        $active = $categorie->active;

        $result = $mysqli->query("INSERT INTO categorie(naam, active) VALUES ('$naam', '$active')");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        mysqli_close($mysqli);
    }

    static function updateCategorie($categorie){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        
        $id = $categorie->id;
        $naam = addslashes($categorie->naam);
        $active = $categorie->active;

        $result = $mysqli->query("UPDATE categorie SET id = '$id', naam = '$naam', active = '$active' WHERE id = $id");
        if (!$result) {
            /*printf("Error: %s\n", mysqli_error($mysqli));*/
            mysqli_close($mysqli);
            return false;
        }
        mysqli_close($mysqli);
        return true;
    }

    static function deleteCategorie($id){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("DELETE FROM categorie WHERE id='$id' AND active=1");
        if (!$result) {
            /*printf("Error: %s\n", mysqli_error($mysqli));*/
            mysqli_close($mysqli);
            return false;
        }
        mysqli_close($mysqli);
        return true;
    }
}

?>

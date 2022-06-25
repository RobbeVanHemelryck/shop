<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class BestellingModel {
    static function getAllBestellingen() {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM bestellingen order by datum DESC");
        $bestellingen = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $lever_straat = $row[1];
            $lever_huisnummer = $row[2];
            $lever_gemeente = $row[3];
            $lever_postcode = $row[4];
            $factuur_straat = $row[5];
            $factuur_huisnummer = $row[6];
            $factuur_gemeente = $row[7];
            $factuur_postcode = $row[8];
            $levermethode_id = $row[9];
            $betaalmethode_id = $row[10];
            $user_id = $row[11];
            $datum = $row[12];
            $totaal = $row[13];

            $bestelling = new BestellingEntity($id, $lever_straat, $lever_huisnummer, $lever_gemeente, $lever_postcode, $factuur_straat, $factuur_huisnummer, $factuur_gemeente, $factuur_postcode, $levermethode_id, $betaalmethode_id, $user_id, $datum, $totaal);

            array_push($bestellingen, $bestelling);
        }

        mysqli_close($mysqli);
        return $bestellingen;
    }
    static function getAllBestellingenByUserId($user_id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM bestellingen WHERE user_id = '$user_id' order by datum DESC");
        $bestellingen = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $lever_straat = $row[1];
            $lever_huisnummer = $row[2];
            $lever_gemeente = $row[3];
            $lever_postcode = $row[4];
            $factuur_straat = $row[5];
            $factuur_huisnummer = $row[6];
            $factuur_gemeente = $row[7];
            $factuur_postcode = $row[8];
            $levermethode_id = $row[9];
            $betaalmethode_id = $row[10];
            $user_id = $row[11];
            $datum = $row[12];
            $totaal = $row[13];

            $bestelling = new BestellingEntity($id, $lever_straat, $lever_huisnummer, $lever_gemeente, $lever_postcode, $factuur_straat, $factuur_huisnummer, $factuur_gemeente, $factuur_postcode, $levermethode_id, $betaalmethode_id, $user_id, $datum, $totaal);

            array_push($bestellingen, $bestelling);
        }


        mysqli_close($mysqli);
        return $bestellingen;
    }
    static function getBestelling($id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM bestellingen WHERE id='$id'");
        $bestelling = null;

        if (!$result) {
            /*printf("Error: %s\n", mysqli_error($mysqli));*/
            mysqli_close($mysqli);
            return false;
        }
        if($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $lever_straat = $row[1];
            $lever_huisnummer = $row[2];
            $lever_gemeente = $row[3];
            $lever_postcode = $row[4];
            $factuur_straat = $row[5];
            $factuur_huisnummer = $row[6];
            $factuur_gemeente = $row[7];
            $factuur_postcode = $row[8];
            $levermethode_id = $row[9];
            $betaalmethode_id = $row[10];
            $user_id = $row[11];
            $datum = $row[12];
            $totaal = $row[13];

            $bestelling = new BestellingEntity($id, $lever_straat, $lever_huisnummer, $lever_gemeente, $lever_postcode, $factuur_straat, $factuur_huisnummer, $factuur_gemeente, $factuur_postcode, $levermethode_id, $betaalmethode_id, $user_id, $datum, $totaal);
        }

        mysqli_close($mysqli);
        return $bestelling;
    }
    static function addBestelling($bestelling){
        require "Credentials.php";
        $datum = date('Y-m-d H:i:s', strtotime($bestelling->datum));

        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("INSERT INTO bestellingen (lever_straat, lever_huisnummer, lever_gemeente, lever_postcode, factuur_straat, factuur_huisnummer, factuur_gemeente, factuur_postcode, levermethode_id, betaalmethode_id, user_id, datum, totaal) VALUES ('$bestelling->lever_straat', '$bestelling->lever_huisnummer', '$bestelling->lever_gemeente', '$bestelling->lever_postcode', '$bestelling->factuur_straat', '$bestelling->factuur_huisnummer', '$bestelling->factuur_gemeente', '$bestelling->factuur_postcode', '$bestelling->levermethode_id', '$bestelling->betaalmethode_id', '$bestelling->user_id', '$datum', '$bestelling->totaal')");

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

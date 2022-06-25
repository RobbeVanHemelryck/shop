<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

class ConfiguratieModel {
    static function getConfiguratie() {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM configuratie");
        $conf = null;

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        if($row = mysqli_fetch_array($result)) {
            $conf = new ConfiguratieEntity($row[0], $row[1], $row[2]);
        }

        mysqli_close($mysqli);
        return $conf;
    }
    static function updateConfiguratie($conf) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        $winkel_naam = addslashes($conf->winkel_naam);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("UPDATE configuratie SET winkel_naam = '$winkel_naam', aantal_uitgelicht = '$conf->aantal_uitgelicht', aantal_nieuwste = '$conf->aantal_nieuwste'");
        $bestellingen = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        mysqli_close($mysqli);
    }
}

?>

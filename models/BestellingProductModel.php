<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/includes.php";

class BestellingProductModel {
    static function addBestellingProduct($bestellingProduct){
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("INSERT INTO bestellingen_producten (bestelling_id, product_id, aantal) VALUES ('$bestellingProduct->bestellingId', '$bestellingProduct->productId', '$bestellingProduct->aantal')");
        if (!$result) {
            printf("Error jaajaa: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }

        mysqli_close($mysqli);
    }
    static function getBestellingProductenIds($bestellingId){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result = $mysqli->query("SELECT * FROM bestellingen_producten WHERE bestelling_id = $bestellingId");
        $producten = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $bestellingId = $row[0];
            $productId = $row[1];
            $aantal = $row[2];

            $bestellingProduct = new BestellingProductEntity($bestellingId, $productId, $aantal);

            array_push($producten, $bestellingProduct);
        }

        mysqli_close($mysqli);
        return $producten;
    }
}

?>

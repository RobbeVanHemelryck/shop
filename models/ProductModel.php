<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

class ProductModel {
    static function getAllProducten($active = true) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $q = "";
        if($active) $q = "SELECT * FROM producten WHERE active=1";
        else $q = "SELECT * FROM producten";

        $result = $mysqli->query($q);
        $producten = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $categorie = $row[1];
            $naam = $row[2];
            $prijs = $row[3];
            $beschrijving = $row[4];
            $datum_toegevoegd = $row[5];
            $img_path = $row[6];
            $uitgelicht = $row[7];
            $rating = $row[8];
            $aantal_ratings = $row[9];
            $active = $row[10];

            $product = new ProductEntity($id, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, $rating, $aantal_ratings, $active);

            array_push($producten, $product);
        }

        mysqli_close($mysqli);
        return $producten;
    }

    static function getAllUitgelichteProducten() {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM producten WHERE uitgelicht = 1 AND active=1");
        $producten = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $categorie = $row[1];
            $naam = $row[2];
            $prijs = $row[3];
            $beschrijving = $row[4];
            $datum_toegevoegd = $row[5];
            $img_path = $row[6];
            $uitgelicht = $row[7];
            $rating = $row[8];
            $aantal_ratings = $row[9];
            $active = $row[10];

            $product = new ProductEntity($id, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, $rating, $aantal_ratings, $active);

            array_push($producten, $product);
        }

        mysqli_close($mysqli);
        return $producten;
    }

    static function getAllProductenByCategorie($cat_id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result = $mysqli->query("SELECT * FROM producten WHERE categorie = '$cat_id' AND active=1");

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }

        $producten = [];
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $categorie = $row[1];
            $naam = $row[2];
            $prijs = $row[3];
            $beschrijving = $row[4];
            $datum_toegevoegd = $row[5];
            $img_path = $row[6];
            $uitgelicht = $row[7];
            $rating = $row[8];
            $aantal_ratings = $row[9];
            $active = $row[10];

            $product = new ProductEntity($id, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, $rating, $aantal_ratings, $active);

            array_push($producten, $product);
        }

        mysqli_close($mysqli);
        return $producten;

        /*require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);
        $producten = [];

        $stmt = $mysqli->prepare("SELECT * FROM producten WHERE categorie = ?");
        $stmt->bind_param('s', $categorie);

        $result = $stmt->execute();

        if (!$result->get_result()) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }

        while ($product = $result->fetch_assoc()) {
            $id = $product["id"];
            $categorie = $product["categorie"];
            $naam = $product["naam"];
            $prijs = $product["prijs"];
            $beschrijving = $product["beschrijving"];
            $datum_toegevoegd = $product["datum_toegevoegd"];
            $img_path = $product["img_path"];
            $uitgelicht = $product["uitgelicht"];
            $rating = $product["rating"];
            $aantal_ratings = $product["aantal_ratings"];

            $product = new ProductEntity($id, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, $rating, $aantal_ratings);

            array_push($producten, $product);
        }

        $stmt->close();
        $mysqli->close();

        return $producten;*/
    }

    static function getProduct($id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM producten WHERE id='$id'");
        $product = null;

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        if ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $categorie = $row[1];
            $naam = $row[2];
            $prijs = $row[3];
            $beschrijving = $row[4];
            $datum_toegevoegd = $row[5];
            $img_path = $row[6];
            $uitgelicht = $row[7];
            $rating = $row[8];
            $aantal_ratings = $row[9];
            $active = $row[10];

            $product = new ProductEntity($id, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, $rating, $aantal_ratings, $active);
        }

        mysqli_close($mysqli);
        return $product;
    }

    static function deleteProduct($id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("DELETE FROM producten WHERE id='$id' AND active=1");

        if (!$result) {
            /*printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();*/
            return false;
        }

        mysqli_close($mysqli);
        return true;
    }

    static function addProduct($product){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $categorie = addslashes($product->categorie);
        $naam = addslashes($product->naam);
        $prijs = $product->prijs;
        $beschrijving = addslashes($product->beschrijving);
        $datum_toegevoegd = $product->datum_toegevoegd;
        $img_path = addslashes($product->img_path);
        $uitgelicht = $product->uitgelicht;
        $rating = $product->rating;
        $aantal_ratings = $product->aantal_ratings;
        $active = $product->active;

        $result = $mysqli->query("INSERT INTO producten(categorie, naam, prijs, beschrijving, datum_toegevoegd, img_path, uitgelicht, rating, aantal_ratings, active) VALUES ('$categorie', '$naam', '$prijs', '$beschrijving', '$datum_toegevoegd', '$img_path', '$uitgelicht', '$rating', '$aantal_ratings', '$active')");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }

        $id = mysqli_insert_id($mysqli);

        mysqli_close($mysqli);

        return $id;
    }
    static function updateProduct($product){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $categorie = addslashes($product->categorie);
        $naam = addslashes($product->naam);
        $prijs = $product->prijs;
        $beschrijving = addslashes($product->beschrijving);
        $datum_toegevoegd = $product->datum_toegevoegd;
        $img_path = addslashes($product->img_path);
        $uitgelicht = $product->uitgelicht;
        $rating = $product->rating;
        $aantal_ratings = $product->aantal_ratings;
        $active = $product->active;

        $result = $mysqli->query("UPDATE producten SET categorie = '$categorie', naam = '$naam', prijs = '$prijs', beschrijving = '$beschrijving', datum_toegevoegd = '$datum_toegevoegd', img_path = '$img_path', uitgelicht = '$uitgelicht', rating = '$rating', aantal_ratings = '$aantal_ratings', active = '$active' WHERE id = '$product->id'");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            return false;
        }
        mysqli_close($mysqli);
        return true;
    }

    static function search($kolom, $keyword){
        $keyword = strtolower($keyword);
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM producten WHERE $kolom LIKE '%$keyword%' AND active=1");
        $producten = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $categorie = $row[1];
            $naam = $row[2];
            $prijs = $row[3];
            $beschrijving = $row[4];
            $datum_toegevoegd = $row[5];
            $img_path = $row[6];
            $uitgelicht = $row[7];
            $rating = $row[8];
            $aantal_ratings = $row[9];
            $active = $row[10];

            $product = new ProductEntity($id, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, $rating, $aantal_ratings, $active);

            array_push($producten, $product);
        }

        mysqli_close($mysqli);
        return $producten;
    }
}

?>

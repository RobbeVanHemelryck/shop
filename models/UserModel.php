<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

class UserModel {
    static function addUser($registeredUser){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("INSERT INTO users (password, naam, voornaam, authority, email, facebook_id, img_path, active) VALUES ('$registeredUser->password', '$registeredUser->lastname', '$registeredUser->firstname', '$registeredUser->authority', '$registeredUser->email', '$registeredUser->facebook_id', '$registeredUser->img_path', '$registeredUser->active')");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }

        $id = mysqli_insert_id($mysqli);

        mysqli_close($mysqli);

        return $id;
    }
    static function updateUser($updatedUser){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("UPDATE users SET password = '$updatedUser->password', naam = '$updatedUser->lastname', voornaam = '$updatedUser->firstname', authority = '$updatedUser->authority', email = '$updatedUser->email', facebook_id = '$updatedUser->facebook_id', img_path = '$updatedUser->img_path', active = '$updatedUser->active' WHERE id = $updatedUser->id");

        if (!$result) {
            /*printf("Error: %s\n", mysqli_error($mysqli));*/
            mysqli_close($mysqli);
            return false;
        }

        mysqli_close($mysqli);
        return true;
    }
    static function getUserByEmail($email) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM users where email='$email'");
        $user = null;

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        if ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $password = $row[1];
            $naam = $row[2];
            $voornaam = $row[3];
            $authority = $row[4];
            $emailadres = $row[5];
            $facebook_id = $row[6];
            $img_path = $row[7];
            $active = $row[8];

            $user = new UserEntity($id, $password, $naam, $voornaam, $authority, $emailadres, $facebook_id, $img_path, $active);
        }

        mysqli_close($mysqli);
        return $user;
    }
    static function getUser($id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM users where id='$id'");
        $user = null;

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        if ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $password = $row[1];
            $naam = $row[2];
            $voornaam = $row[3];
            $authority = $row[4];
            $emailadres = $row[5];
            $facebook_id = $row[6];
            $img_path = $row[7];
            $active = $row[8];

            $user = new UserEntity($id, $password, $naam, $voornaam, $authority, $emailadres, $facebook_id, $img_path, $active);
        }

        mysqli_close($mysqli);
        return $user;
    }
    static function getUserByFacebookId($facebook_id) {
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM users where facebook_id='$facebook_id'");
        $user = null;

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        if ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $password = $row[1];
            $naam = $row[2];
            $voornaam = $row[3];
            $authority = $row[4];
            $emailadres = $row[5];
            $facebook_id = $row[6];
            $img_path = $row[7];
            $active = $row[8];

            $user = new UserEntity($id, $password, $naam, $voornaam, $authority, $emailadres, $facebook_id, $img_path, $active);
        }

        mysqli_close($mysqli);
        return $user;
    }
    static function getAllUsers() {
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM users");
        $users = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $password = $row[1];
            $naam = $row[2];
            $voornaam = $row[3];
            $authority = $row[4];
            $emailadres = $row[5];
            $facebook_id = $row[6];
            $img_path = $row[7];
            $active = $row[8];

            $user = new UserEntity($id, $password, $naam, $voornaam, $authority, $emailadres, $facebook_id, $img_path, $active);

            array_push($users, $user);
        }

        mysqli_close($mysqli);
        return $users;
    }
}

?>

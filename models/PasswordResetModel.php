<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

class PasswordResetModel {
    static function addPasswordReset($passwordReset){
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("INSERT INTO password_reset (email, datum) VALUES ('$passwordReset->email', '$passwordReset->datum')");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }

        $id = mysqli_insert_id($mysqli);
        mysqli_close($mysqli);

        return $id;
    }

    static function getPasswordReset($id){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result = $mysqli->query("SELECT * FROM password_reset WHERE id = $id");
        $passwordReset = null;

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        if($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $email = $row[1];
            $datum = $row[2];

            $passwordReset = new PasswordResetEntity($id, $email, $datum);
        }

        mysqli_close($mysqli);
        return $passwordReset;
    }

    static function getPasswordResetsByEmail($email){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM password_reset WHERE email = '$email' ORDER BY datum DESC");
        $passwordResets = [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $email = $row[1];
            $datum = $row[2];

            $passwordReset = new PasswordResetEntity($id, $email, $datum);
            array_push($passwordResets, $passwordReset);
        }

        mysqli_close($mysqli);
        return $passwordResets;
    }

    static function deletePasswordReset($id){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result = $mysqli->query("DELETE FROM password_reset WHERE id = $id");

        if (!$result) {
            //printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            return false;
        }

        mysqli_close($mysqli);
        return true;
    }
    static function deletePasswordResetByEmail($email){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $result = $mysqli->query("DELETE FROM password_reset WHERE email = $email");

        if (!$result) {
            //printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            return false;
        }

        mysqli_close($mysqli);
        return true;
    }
}

?>

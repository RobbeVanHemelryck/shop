<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/shop/includes.php";

class ReviewModel {
    static function addReview($toAddReview){
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $comment = addslashes($toAddReview->comment);
        $title = addslashes($toAddReview->title);

        $result = $mysqli->query("INSERT INTO reviews (user_id, product_id, comment, rating, datum, title) VALUES ('$toAddReview->user_id', '$toAddReview->product_id', '$comment', '$toAddReview->rating', '$toAddReview->datum', '$title')");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        mysqli_close($mysqli);
    }

    static function getAllReviews($id) {
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM reviews where product_id = '$id'");
        $reviewArray =  [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $user_id = $row[1];
            $product_id = $row[2];
            $comment = $row[3];
            $rating = $row[4];
            $datum = $row[5];
            $title = $row[6];
            
            $review = new ReviewEntity($id, $user_id, $product_id, $comment, $rating, $datum, $title);
            array_push($reviewArray, $review);
        }

        mysqli_close($mysqli);
        return $reviewArray;
    }

    static function getReviewsByProductAndUser($product_id, $user_id) {
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM reviews where product_id = '$product_id' AND user_id = '$user_id'");
        $reviewArray =  [];

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        while ($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $user_id = $row[1];
            $product_id = $row[2];
            $comment = $row[3];
            $rating = $row[4];
            $datum = $row[5];
            $title = $row[6];
            
            $review = new ReviewEntity($id, $user_id, $product_id, $comment, $rating, $datum, $title);
            array_push($reviewArray, $review);
        }

        mysqli_close($mysqli);
        return $reviewArray;
    }

    static function getReview($id) {
        require "Credentials.php";
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("SELECT * FROM reviews where id = '$id'");
        $review = null;

        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        if($row = mysqli_fetch_array($result)) {
            $id = $row[0];
            $user_id = $row[1];
            $product_id = $row[2];
            $comment = $row[3];
            $rating = $row[4];
            $datum = $row[5];
            $title = $row[6];
            
            $review = new ReviewEntity($id, $user_id, $product_id, $comment, $rating, $datum, $title);
        }

        mysqli_close($mysqli);
        return $review;
    }

    static function updateReview($review){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $comment = addslashes($review->comment);
        $title = addslashes($review->title);
        $rating = $review->rating;
        $datum = $review->datum;

        $result = $mysqli->query("UPDATE reviews SET rating = '$rating', comment = '$comment', title = '$title', datum = '$datum' WHERE id = $review->id");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        mysqli_close($mysqli);
    }

    static function removeReview($reviewId){
        require 'Credentials.php';
        $mysqli = new mysqli($host, $user, $passwd, $database);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $mysqli->query("DELETE FROM reviews WHERE id = $reviewId");
        if (!$result) {
            printf("Error: %s\n", mysqli_error($mysqli));
            mysqli_close($mysqli);
            exit();
        }
        mysqli_close($mysqli);
    }
}

?>

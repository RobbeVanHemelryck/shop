<?php

class ReviewEntity
{
    public $id;
    public $user_id;
    public $product_id;
    public $comment;
    public $rating;
    public $datum;
    public $title;
    
    function __construct($id, $user_id, $product_id, $comment, $rating, $datum, $title) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->comment = $comment;
        $this->rating = $rating;
        $this->datum = $datum;
        $this->title = $title;
    }
}

?>
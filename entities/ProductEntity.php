<?php

class ProductEntity
{
    public $id;
    public $categorie;
    public $naam;
    public $prijs;
    public $beschrijving;
    public $datum_toegevoegd;
    public $img_path;
    public $uitgelicht;
    public $rating;
    public $aantal_ratings;
    public $active;
    
    function __construct($id, $categorie, $naam, $prijs, $beschrijving, $datum_toegevoegd, $img_path, $uitgelicht, $rating, $aantal_ratings, $active) {
        $this->id = $id;
        $this->categorie = $categorie;
        $this->naam = $naam;
        $this->prijs = $prijs;
        $this->beschrijving = $beschrijving;
        $this->datum_toegevoegd = $datum_toegevoegd;
        $this->img_path = $img_path;
        $this->uitgelicht = $uitgelicht;
        $this->rating = $rating;
        $this->aantal_ratings = $aantal_ratings;
        $this->active = $active;
    }
}

?>

<?php

class CategorieEntity
{
	public $id;
    public $naam;
    public $active;
    
    function __construct($id, $naam, $active) {
    	$this->id = $id;
        $this->naam = $naam;
        $this->active = $active;
    }
}

?>

<?php

class BetaalmethodeEntity
{
	public $id;
    public $naam;
    public $kosten_geld;
    public $kosten_procent;
    public $img_path;
    
    function __construct($id, $naam, $kosten_geld, $kosten_procent, $img_path) {
    	$this->id = $id;
        $this->naam = $naam;
        $this->kosten_geld = $kosten_geld;
        $this->kosten_procent = $kosten_procent;
        $this->img_path = $img_path;
    }
}

?>

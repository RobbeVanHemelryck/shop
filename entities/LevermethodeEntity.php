<?php

class LevermethodeEntity
{
	public $id;
    public $naam;
    public $kosten_geld;
    public $kosten_procent;
    public $img_path;
    public $duur;
    
    function __construct($id, $naam, $kosten_geld, $kosten_procent, $img_path, $duur) {
    	$this->id = $id;
        $this->naam = $naam;
        $this->kosten_geld = $kosten_geld;
        $this->kosten_procent = $kosten_procent;
        $this->img_path = $img_path;
        $this->duur = $duur;
    }
}

?>

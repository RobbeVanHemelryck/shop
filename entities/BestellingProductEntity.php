<?php

class BestellingProductEntity
{
    public $bestellingId;
    public $productId;
    public $aantal;
    
    function __construct($bestellingId, $productId, $aantal){
        $this->bestellingId = $bestellingId;
		$this->productId = $productId;
		$this->aantal = $aantal;
    }
}

?>

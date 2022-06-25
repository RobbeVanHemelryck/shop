<?php

class ConfiguratieEntity
{
    public $winkel_naam;
    public $aantal_uitgelicht;
    public $aantal_nieuwste;
    
    function __construct($winkel_naam, $aantal_uitgelicht, $aantal_nieuwste) {
        $this->winkel_naam = $winkel_naam;
        $this->aantal_uitgelicht = $aantal_uitgelicht;
        $this->aantal_nieuwste = $aantal_nieuwste;
    }
}

?>

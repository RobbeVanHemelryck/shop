<?php

class BestellingEntity
{
    public $id;
    public $lever_straat;
    public $lever_huisnummer;
    public $lever_gemeente;
    public $lever_postcode;
    public $factuur_straat;
    public $factuur_huisnummer;
    public $factuur_gemeente;
    public $factuur_postcode;
    public $levermethode_id;
    public $betaalmethode_id;
    public $user_id;
    public $datum;
    public $totaal;
    
    function __construct($id, $lever_straat, $lever_huisnummer, $lever_gemeente, $lever_postcode, $factuur_straat, $factuur_huisnummer, $factuur_gemeente, $factuur_postcode, $levermethode_id, $betaalmethode_id, $user_id, $datum, $totaal){
        $this->id = $id;
		$this->lever_straat = $lever_straat;
        $this->lever_huisnummer = $lever_huisnummer;
        $this->lever_gemeente = $lever_gemeente;
        $this->lever_postcode = $lever_postcode;
        $this->factuur_straat = $factuur_straat;
        $this->factuur_huisnummer = $factuur_huisnummer;
        $this->factuur_gemeente = $factuur_gemeente;
        $this->factuur_postcode = $factuur_postcode;
		$this->levermethode_id = $levermethode_id;
		$this->betaalmethode_id = $betaalmethode_id;
		$this->user_id = $user_id;
		$this->datum = $datum;
        $this->totaal = $totaal;
    }
}

?>

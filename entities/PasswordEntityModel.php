<?php

class PasswordResetEntity
{
    public $id;
    public $email;
    public $datum;
    
    function __construct($id, $email, $datum){
        $this->id = $id;
		$this->email = $email;
		$this->datum = $datum;
    }
}

?>

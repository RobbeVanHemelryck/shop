<?php

class UserEntity
{
    public $id;
    public $password;
    public $lastname;
    public $firstname;
    public $authority;
    public $email;
    public $facebook_id;
    public $img_path;
    public $active;

    function __construct($id, $password, $lastname, $firstname, $authority, $email, $facebook_id, $img_path, $active) {
        $this->id = $id;
        $this->password = $password;
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->authority = $authority;
        $this->email = $email;
        $this->facebook_id = $facebook_id;
        $this->img_path = $img_path;
        $this->active = $active;
    }
}

?>
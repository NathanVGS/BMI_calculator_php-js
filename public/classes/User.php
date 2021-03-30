<?php


class User
{
    private $email;
    private $userName;
    public function __construct(string $email, string $userName){
        $this->email = $email;
        $this->userName = $userName;
    }
}
<?php
//Simulacion de modelo Paypal para agregar al service container
namespace App\Models;

class Paypal {
    private $id;
    private $secret;

    public function __construct($id, $secret)   
    {
        $this->id = $id;
        $this->secret = $secret;
    }

    public function doSomething() {
        return 'I did a payment';
    }
}
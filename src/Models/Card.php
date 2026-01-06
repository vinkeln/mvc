<?php

// card/deck/shuffle som visar samtliga kort i kortleken när den har blandats.

namespace App\Models;

class Card
{
    protected $value;
    protected $suit;
    protected $color;
    


    public function __construct()
    {
        
    }

    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}

<?php

// card/deck/shuffle som visar samtliga kort i kortleken när den har blandats.
namespace App\Models;

class Card
{
    protected $value;
    protected $suit;
    

    public function __construct()
    {

    }
    

    public function shuffle(): int
    {
        $this->value = random_int(1, 46);
        return $this->value;
    }

    public function getAsString(): string
    {
        return "[{$this->value}]";
    }
}
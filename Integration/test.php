<?php namespace App\Integration ;

class mine {
    public array $data = [ 1 , 2 , 3 ] ;
}

class test{

    public function __construct( public mine $mine = new mine ) { }

}
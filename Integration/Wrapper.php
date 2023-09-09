<?php namespace App\Integration ;

use App\Inject\Parameters;

class slave {
    public array $data = [ 1 , 2 , 3 ] ;
}

#[ Parameters( [ 'slave' => slave::class ] ) ]
class Wrapper{
    public slave $slave ;
}
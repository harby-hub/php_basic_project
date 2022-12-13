<?php namespace App\Support ;

use App\Support\Url;

class Curl{

    public ?\CurlHandle $handler = null  ;
    public string       $url     = ''    ;
    public array        $data    = []    ;
    public array        $query   = []    ;
    public string       $method  = 'get' ;
    public string       $content = ''    ;

    public static function new( ) : self {
        return new static ;
    }

    public function url( string $url = '' ) : self {
        $this -> url = $url ;
        return $this ;
    }

    public function data( array $data = [ ] ) : self {
        $this -> data = $data ;
        return $this ;
    }

    public function query( array $query = [ ] ){
        $this -> query = $query ;
        return $this ;
    }

    public function method( string $method = 'get' ){
        $this -> method = strtoupper( $method );
        return $this;
    }

    // function that will send our request
    public function send( ) {
        try{
            if( $this -> handler == null ) $this -> handler = curl_init( ) ;
            $array = match( strtolower( $this -> method ) ){
                'post'                     => [ CURLOPT_POST    => count ( $this -> data ) ] ,
                'get' , 'head'             => [ CURLOPT_HTTPGET => true                    ] ,
                'put' , 'patch' , 'delete' => [ ] ,
            } + [
                CURLOPT_POSTFIELDS     => http_build_query( $this -> data                 ) ,
                CURLOPT_CUSTOMREQUEST  => strtoupper      ( $this -> method               ) ,
                CURLOPT_URL            => Url::build      ( $this -> url , $this -> query ) ,
                CURLOPT_RETURNTRANSFER => true
            ] ;
            curl_setopt_array ( $this -> handler , $array );
            $this -> content = curl_exec    ( $this -> handler );
            $this -> info    = curl_getinfo ( $this -> handler );
            return $this ;
        } catch( \Exception $e ){ die( $e -> getMessage( ) ) ; }
    }		

    // function that will close the connection of the curl handler
    public function close( ) {
        curl_close ( $this -> handler ) ;
        $this -> handler = null ;
    }

}
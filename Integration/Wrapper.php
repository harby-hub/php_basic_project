<?php namespace App\Integration ;

use App\Inject\Parameters;
use App\Support\Curl;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\TransportException;


class slave {
    public array $data = [ 1 , 2 , 3 ] ;
}

#[ Parameters( [ 'slave' => slave::class ] ) ]
class Wrapper{

    public slave $slave ;

    public function fopen( string $method , string $url , array $query = [ ] ) : array {
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => strtoupper( $method ) ,
                'content' => http_build_query( $query )
            )
        );
        $mad = @stream_context_create( $options );
        $fp  = @fopen( \App\Support\Url::build( $url , $query ) , 'rb' , false , $mad );
        try {
            $respone = ( array ) json_decode( stream_get_contents( $fp ) , true ) ;
            fclose( $fp ) ;
            return $respone ;
        } catch ( \Throwable $th ) {
            return [
                ( string ) $th -> getMessage( )  ,
                $th -> getTrace( )  ,
                ( string ) $th ,
            ]  ;
        }
    }

    public function file_get_contents( string $method , string $url , array $query = [ ] ) : array {
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => strtoupper( $method ) ,
                'content' => http_build_query( $query )
            )
        );
        $result = file_get_contents( \App\Support\Url::build( $url , $query ) , false, stream_context_create( $options ) ) ;
        try {
            $respone = ( array ) json_decode( $result , true ) ;
            return $respone ;
        } catch ( \Throwable $th ) {
            return [
                ( string ) $th -> getMessage( )  ,
                $th -> getTrace( )  ,
                ( string ) $th ,
            ]  ;
        }
    }

    public function curl( string $method , string $url , array $query = [ ] ) : array {
        $ch = Curl::new( )
            -> url    ( $url    )
            -> query  ( $query  )
            -> method ( $method )
            -> data   ( $query  )
            -> send   (         )
            -> content
        ;
        return ( array ) json_decode( $ch  , true ) ;
    }

    public function Client( string $method , string $url , array $query = [ ] ) : array {
        try{   
            $response = ( new Client( ) ) -> request( strtoupper( $method ) , $url , [ 'query' => $query ] ) ;
        } catch ( ConnectException $th) {
            return [
                ( string ) $th -> getMessage( )  ,
                $th -> getTrace( )  ,
                ( string ) $th ,
            ]  ;
        }
        return ( array ) json_decode( $response -> getBody( ) -> getContents( ) , true ) ;
    }

    public function HttpClient( string $method , string $url , array $query = [ ] ) : array {
        try {   
            return HttpClient::create( [ 'max_redirects' => 3 ] ) -> request( strtoupper( $method ) , $url , [ 'query' => $query ] ) -> toArray( ) ;
        } catch ( TransportException $th ) {
            return [
                ( string ) $th -> getMessage( )  ,
                $th -> getTrace( )  ,
                ( string ) $th ,
            ]  ;
        } catch ( \Symfony\Component\HttpClient\Exception\JsonException $th ) {
            return [
                ( string ) $th -> getMessage( )  ,
                $th -> getTrace( )  ,
                ( string ) $th ,
            ]  ;
        }
    }

}
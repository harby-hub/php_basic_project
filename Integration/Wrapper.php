<?php namespace App\Integration ;

use GuzzleHttp\Client;
use App\Inject\Parameters;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Exception\TransportException;


class slave {
    public array $data = [ 1 , 2 , 3 ] ;
}

// http://localhost:800/api/Cities/?id%5B0%5D=1&id%5B1%5D=2&first=2
// http://localhost:800/api/Cities?id%5B0%5D=1&id%5B1%5D=2&first=2

#[ Parameters( [ 'slave' => slave::class ] ) ]
class Wrapper{

    public slave $slave ;

    public function fopen( string $url , array $query = [ ] ) : array {
        $url = sprintf( '%s?%s', $url , http_build_query( $query ) ) ;
        $fp = fopen( $url , 'r' );
        try {
            $respone = ( array ) json_decode( stream_get_contents( $fp ) , true ) ;
            fclose( $fp ) ;
            return $respone + [ 'url' => $url ] ;;
        } catch (\Throwable $th) {
            return [$th]  ;
        } finally {
        }
    }

    public function curl( string $url , array $query = [ ] ) : array {
        $url = sprintf( '%s?%s', $url , http_build_query( $query ) ) ;
        $ch = curl_init( ) ;
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER  , true ) ;
        curl_setopt( $ch , CURLOPT_URL             , $url ) ;
        curl_setopt( $ch , CURLOPT_SSH_COMPRESSION , true ) ;
        curl_setopt_array( $ch , [
            CURLOPT_RETURNTRANSFER => true ,
            CURLOPT_URL            => $url
        ] ) ;
        $result = ( array ) json_decode( curl_exec( $ch ) , true ) ;
        curl_close( $ch );
        return $result + [ 'url' => $url ] ;
    }

    public function Client( string $url , array $query = [ ] ) : array {
        try{   
            $response = ( new Client( ) ) -> request( 'GET' , $url , [ 'query' => $query ,
            'on_stats' => function (TransferStats $stats) use (&$url) {
                $url = $stats->getEffectiveUri();
            } ] ) ;
        } catch ( ConnectException $th) {
            return [$th]  ;
        }
        return ( array ) json_decode( $response -> getBody( ) -> getContents( ) , true ) + [ 'url' => $url ] ;
    }

    public function HttpClient( string $url , array $query = [ ] ) : array {
        try{   
            $response = HttpClient::create( [ 'max_redirects' => 3 ] ) -> request( 'GET' , $url , [ 'query' => $query ] ) ;
            return $response -> toArray( ) + [ 'url' => $response->getInfo()['url'] ] ;
        } catch ( TransportException $th) {
            return [$th]  ;
        }
    }

}
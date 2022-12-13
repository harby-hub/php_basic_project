<?php require __DIR__ . '/vendor/autoload.php';

use App\Router\Router ;
use App\Controllers\Controller ;

function responce( array $data ) : void {
    header( "Content-Type: application/json; charset=UTF-8" );
    echo json_encode( $data ) ;
    exit ;
} 

function dd( ... $data ) : void {
    dump( $data ) ;
    exit ;
} 

( Dotenv\Dotenv::createImmutable( __DIR__ ) ) -> load( ) ;

Router::bind ( [ Controller::class ] ) -> run( );
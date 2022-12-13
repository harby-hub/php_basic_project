<?php namespace App\Support ; class Response {

    static function factory( ) : self {
        return new static ;
    }

    /**
     * make response json with return it and exit app
     */
    static function json( array $data ) : void {
        header( "Content-Type: application/json; charset=UTF-8" );
        echo json_encode( $data ) ;
        exit ;
    } 

}
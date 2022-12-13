<?php namespace App\Controllers;

use App\Support\Router\Route;
use App\Integration\{Wrapper,test};
use App\Inject\{Parameters,Agreements};

#[ Parameters( [ 'Wrapper' => Wrapper::class ] ) ]
class Controller {

    public Wrapper $Wrapper ;

    #[ Route( '/' , name : 'homepage' , methods : [ 'GET' , 'POST' ] ) ]
    public function home( ) : array { return [
        'url'     => 'home'           ,
        'Wrapper' => $this -> Wrapper ,
        'ENV'     => $_ENV            ,
    ] ; }

    #[
        Agreements( [ 'test' => test::class ] ) ,
        Route( '/article/{slug}/comment/{id<\d+>}' , name: 'article-comment' )
    ]
    public function comment( test $test , int $id , string $slug ) : array {
        $array = [ 
            'id'    => [ 1 , 2 ] ,
            'first' =>   2
        ];
        $url = 'https://jsonplaceholder.typicode.com/todos/1' ;
        $url = 'http://localhost:800/api/v1/test' ;
        $method = 'get' ;
        return [
            'url'               => '/article/{slug}/comment/{id<\d+>}'                               ,
            'slug'              => $slug                                                             ,
            'id'                => $id                                                               ,
            'test'              => $test                                                             ,
            'mine'              => $test -> mine                                                     ,
            'Wrapper'           => $this -> Wrapper                                                  ,
            'file_get_contents' => $this -> Wrapper -> file_get_contents ( $method , $url , $array ) ,
            'fopen'             => $this -> Wrapper -> fopen             ( $method , $url , $array ) ,
            'curl'              => $this -> Wrapper -> curl              ( $method , $url , $array ) ,
            'Client'            => $this -> Wrapper -> Client            ( $method , $url , $array ) ,
            'HttpClient'        => $this -> Wrapper -> HttpClient        ( $method , $url , $array ) ,
            'ENV'               => $_ENV                               ,
        ] ;
    }
}
<?php namespace App\Controllers;

use App\Router\Route;
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
        $url = 'http://localhost:800/api/Cities' ;
        return [
            'url'     => '/article/{slug}/comment/{id<\d+>}' ,
            'slug'    => $slug                               ,
            'id'      => $id                                 ,
            'test'    => $test                               ,
            'mine'    => $test -> mine                       ,
            'Wrapper' => $this -> Wrapper                    ,
            'fopen'   => $this -> Wrapper -> fopen          ( $url , $array ) ,
            'curl'    => $this -> Wrapper -> curl           ( $url , $array ) ,
            'Client'  => $this -> Wrapper -> Client         ( $url , $array ) ,
            'HttpClient'  => $this -> Wrapper -> HttpClient ( $url , $array ) ,
            'ENV'     => $_ENV                               ,
        ] ;
    }
}
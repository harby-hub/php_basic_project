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
        return [
            'url'     => '/article/{slug}/comment/{id<\d+>}' ,
            'slug'    => $slug                               ,
            'id'      => $id                                 ,
            'test'    => $test                               ,
            'mine'    => $test -> mine                       ,
            'Wrapper' => $this -> Wrapper                    ,
            'ENV'     => $_ENV                               ,
        ] ;
    }
}
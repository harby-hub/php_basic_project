<?php namespace App\Controllers;

use App\Router\Route;
use App\Inject\inject;
use App\Integration\Wrapper;
use App\Inject\Parameters;
use App\Integration\test;

#[ inject( [ 'Wrapper' => Wrapper::class ] ) ]
class Controller {

    public Wrapper $Wrapper ;

    #[ Route( '/' , name : 'homepage' , methods : [ 'GET' , 'POST' ] ) ]
    public function home( ) : array { return [
        'url'     => 'home'           ,
        'Wrapper' => $this -> Wrapper ,
        'ENV'     => $_ENV            ,
    ] ; }

    #[
        inject( [ 'test' => test::class ] ) ,
        Route( '/article/{slug}/comment/{id<\d+>}' , name: 'article-comment' )
    ]
    public function comment( string $slug , int $id , test $test ) : array { return [
        'url'     => '/article/{slug}/comment/{id<\d+>}' ,
        'slug'    => $slug                               ,
        'id'      => $id                                 ,
        'test'    => $test                               ,
        'mine'    => $test -> mine                       ,
        'Wrapper' => $this -> Wrapper                    ,
        'ENV'     => $_ENV                               ,
    ] ; }
}
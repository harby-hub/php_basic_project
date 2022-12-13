<?php require __DIR__ . '/vendor/autoload.php' ;

( Dotenv\Dotenv::createImmutable( __DIR__ ) ) -> load( ) ;

\App\Support\Router\Router::bind ( [ \App\Controllers\Controller::class ] ) -> run( ) ;
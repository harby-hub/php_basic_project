<?php require __DIR__ . '/vendor/autoload.php' ;

( Dotenv\Dotenv::createImmutable( __DIR__ ) ) -> load( ) ;

\App\Router\Router::bind ( [ \App\Controllers\Controller::class ] ) -> run( ) ;
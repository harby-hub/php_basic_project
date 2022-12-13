<?php namespace App\Inject;

#[ \Attribute( \Attribute::TARGET_FUNCTION ) ]
class Agreements {

    public function __construct( private array $classes ) { }

    public function inject( $value ) {
        $reflectionController = new \ReflectionClass( $value ) ;
        $class = new $value ;
        foreach ( $reflectionController -> getAttributes( static::class ) as $injectAttribute ) foreach ( $injectAttribute -> newInstance( ) -> injects( ) as $key => $value ) $class -> $key = $value ;
        return $class ;
    }

    public function injects( ) {
        $injects = [ ] ;
        foreach ( $this -> classes as $key => $value ) $injects [ $key ] = $this -> inject( $value ) ;
        return $injects ;
    }

}
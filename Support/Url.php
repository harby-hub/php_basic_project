<?php namespace App\Support ; class Url {

    static function factory( ) : self {
        return new static ;
    }

    /**
     * build Url from url and query
     *
     * @param  string        $url
     * @param  string|array  $mixed_data
     * @return string
     */
    static function build( string $url , string | array $mixed_data = [ ] ) : string {
        $query_string = '' ;
        if ( ! empty( $mixed_data ) ) {
            $query_mark = strpos( $url , '?' ) > 0 ? '&' : '?' ;
            if     ( is_string ( $mixed_data ) ) $query_string .= $query_mark . $mixed_data ;
            elseif ( is_array  ( $mixed_data ) ) $query_string .= $query_mark . http_build_query( $mixed_data , '' , '&' ) ;
        }
        return sprintf( '%s%s', $url , $query_string ) ;
    }

}
<?php namespace App\Integration ;

use App\Inject\Parameters;

class slave {
    public array $data = [ 1 , 2 , 3 ] ;
}

#[ Parameters( [ 'slave' => slave::class ] ) ]
class Wrapper{

    public slave $slave ;

    public function response( ) : array {

        $BASE_URL = 'https://api.flickr.com/services';
        $PHOTO_URL = 'https://live.staticflickr.com/%s/%s_%s_b.jpg';
        $PHOTOS_DIR = 'photos';

        $apiKey=$_ENV['API_KEY'];

        $opts = [
            'https' => [
                'max_redirects' => 3,
            ],
        ];
        $queryString = http_build_query([
            'api_key' => $apiKey,
            'content_type' => 1,
            'format' => 'php_serial',
            'media' => 'photos',
            'method' => 'flickr.photos.search',
            'per_page' => 10,
            'safe_search' => 1,
            'text' => 'Kakadu National Park'
        ]);
        $requestUri = sprintf(
            '%s/rest/?%s',
            $BASE_URL,
            $queryString
        );
        $fp = fopen($requestUri, 'r');

        $photoData = unserialize(stream_get_contents($fp));

        foreach ($photoData['photos']['photo'] as $photoDatum) {
            printf("Downloading %s.jpg\n", $photoDatum['title']);
            $photoFile = file_get_contents(
                sprintf(
                    $PHOTO_URL,
                    $photoDatum['server'],
                    $photoDatum['id'],
                    $photoDatum['secret']
                )
            );
            file_put_contents($PHOTOS_DIR . '/' . $photoDatum['title'] . '.jpg', $photoFile);
            $array[ ] = $photoDatum['title'] ;
        }

        fclose($fp);
        return $array ;
    }

}
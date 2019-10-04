<?php
namespace Waryway\ImageServerRest;

class ImageHandler {

    private static $ImagePath;

    /**
     * @return mixed
     */
    public static function getImagePath()
    {
        if(!isset(self::$ImagePath)) {
            self::setImagePath();
        }
        return self::$ImagePath;
    }

    /**
     * @param mixed $ImagePath
     */
    public static function setImagePath($ImagePath=null)
    {
        self::$ImagePath = is_null($ImagePath) ? dirname(__DIR__) . '/assets/images/': $ImagePath;
    }


    public function __construct()
    {
    }

    public function getRoutes() {
        return [
            ['method'=>['GET'], 'route' => '/images', 'handler' => [__CLASS__,'getImageStatistics']],
        ];
    }

    public static function getImageStatistics() {
        $response = [
            'body' => '404',
            'code' => 404,
            'headers' => ['Content-Type' => 'text/html']
        ];



        if (file_exists(self::getImagePath())) {
            // print_r(array_keys($params));
            $response['code'] = 200;
            $response['body'] = self::calculateImageStatistics();
            $response['headers'] = ['Content-Type' => 'application/json'];
        }
        return $response;//new Response($response['code'], $response['headers'], $response['body']);
    }

    /**
     * @return string
     */
    private static function calculateImageStatistics() {
        $result = new \stdClass();
        $dirStats = scandir(self::getImagePath());
        foreach ($dirStats as $entry ){
            switch($entry) {
                case '.':
                case '..':
                    break;
                default:
                    if (is_dir(self::getImagePath().DIRECTORY_SEPARATOR.$entry)) {
                        break;
                    }
                    $result->{$entry} = [
                        "name" => $entry,
                        "size" => filesize(self::getImagePath().DIRECTORY_SEPARATOR.$entry),
                        "type" => filetype(self::getImagePath().DIRECTORY_SEPARATOR.$entry),
                        "url" => "/images/assets/" . $entry,
                    ];
                    break;
                }

            }

        return json_encode($result);
    }
}
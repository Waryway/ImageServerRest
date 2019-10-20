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
            /* @uses ImageHandler::getImageStatistics */
            ['method'=>['GET'], 'route' => '/images', 'handler' => [__CLASS__,'getImageStatistics']],
        ];
    }

    /**
     * @OA\Get(
     *     path="/images",
     *     @OA\Response(response="200", description="Returns available image statistics", @OA\MediaType(
     *         mediaType="application/json",
     *         @OA\Schema(ref="#/components/schemas/ImageMetaData")
     *     )),
     *     @OA\Response(response="404", description="Returns a 404 if the server cannot find the path to the images.", @OA\MediaType(
     *         mediaType="text/html",
     *     ))
     * )
     */
    public static function getImageStatistics() {
        $response = [
            'body' => '404',
            'code' => 404,
            'headers' => ['Content-Type' => 'text/html']
        ];

        if (file_exists(self::getImagePath())) {
            $response['code'] = 200;
            $response['body'] = self::calculateImageStatistics();
            $response['headers'] = ['Content-Type' => 'application/json'];
        }

        return $response;
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
                    $metaData = new ImageMetaData();
                    $metaData->name = $entry;
                    $metaData->size = filesize(self::getImagePath().DIRECTORY_SEPARATOR.$entry);
                    $metaData->type = filetype(self::getImagePath().DIRECTORY_SEPARATOR.$entry);
                    $metaData->url  = "/images/assets/" . $entry;

                    $result->{$entry} = $metaData;
                    break;
                }

            }

        return json_encode($result);
    }
}
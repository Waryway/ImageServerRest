<?php
namespace Waryway\ImageServerRest;

use React\Http\Response;
use Waryway\MicroServiceEngine\BaseRouter;

/**
 * @OA\Info(
 *   title="Image Server Rest",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="kyle@waryway.com"
 *   )
 * )
 */
class Router extends BaseRouter {
    public function __construct() {


        /* @uses Router::swagger */
        $this->setRoute(['GET'], '/swagger.json', [__CLASS__,'swagger']);

        /* @uses Router::helloWorld */
        $this->setRoute(['GET', 'POST', 'PUT', 'DELETE'], '/', [__CLASS__,'helloWorld']);
        $imageHandlerRoutes = (new ImageHandler())->getRoutes();

        foreach ($imageHandlerRoutes as $imageHandlerRoute) {
            $this->setRoute($imageHandlerRoute['method'], $imageHandlerRoute['route'], $imageHandlerRoute['handler']);
        }
        
        parent::__construct();
    }

    /**
     * @OA\Get(
     *     path="/helloWorld",
     *     @OA\Response(response="200", description="Returns Hello World")
     * )
     */
    public function helloWorld($params) {
        return 'Hello World';
    }

    /**
     * @OA\Get(
     *     path="/swagger.json",
     *     @OA\Response(response="200", description="The API's swagger.json will be served."),
     *     @OA\Response(response="404", description="If the swagger.json is not present.")
     * )
     */
    public function swagger($params) {
        $response = [
            'body' => '404',
            'code' => 404
        ];
        if (file_exists(__DIR__.'/swagger.json')) {
            $response['code'] = 200;
            $response['body'] = file_get_contents(__DIR__.'/swagger.json');
            $response['headers'] = ['Content-Type' => 'application/json'];
        }
        return new Response($response['code'], $response['headers'], $response['body']);
    }
}
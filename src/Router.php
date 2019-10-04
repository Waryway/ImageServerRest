<?php
namespace Waryway\ImageServerRest;

use React\Http\Response;
use Waryway\MicroServiceEngine\BaseRouter;

class Router extends BaseRouter {
    public function __construct() {

        $this->setRoute(['GET'], '/swagger.json', 'swagger');
        $this->setRoute(['GET', 'POST', 'PUT', 'DELETE'], '/', 'helloWorld');
        $imageHandlerRoutes = (new ImageHandler())->getRoutes();

        foreach($imageHandlerRoutes as $imageHandlerRoute) {
            $this->setRoute($imageHandlerRoute['method'], $imageHandlerRoute['route'], $imageHandlerRoute['handler']);
        }
        
        parent::__construct();
    }
    
    public function helloWorld($params) {
        // print_r($params);
        return 'Hello World';
    }
    
    public function swagger($params) {
        $response = [
            'body' => '404',
            'code' => 404
        ];
        if (file_exists(__DIR__.'/swagger.json')) {
            // print_r(array_keys($params));
            $response['code'] = 200;
            $response['body'] = file_get_contents(__DIR__.'/swagger.json');
            $response['headers'] = ['Content-Type' => 'application/json'];
        }
        return new Response($response['code'], $response['headers'], $response['body']);
    }
}
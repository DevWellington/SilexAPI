<?php

namespace DevWellington\Shop\Controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiControllerProvider implements ControllerProviderInterface
{
    // todo: Data validate with (http://silex.sensiolabs.org/doc/providers/validator.html)

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        Request::enableHttpMethodParameterOverride();

        $controllers->get('/product/list', function() use($app){
            return $app->json(
                $app['productService']->fetchAll()
            );
        })
            ->bind('get-api-product-list')
        ;

        $controllers->get('/product/{id}', function($id) use($app){
            return $app->json(
                $app['productService']->fetch($id)
            );
        })
            ->bind('get-api-product-unique')
        ;

        $controllers->post('/product/', function(Request $request) use($app){

            $result = $app['productService']->insert(
                [
                    'name' => $request->request->get('name'),
                    'description' => $request->request->get('description'),
                    'value' => $request->request->get('value')
                ]
            );

            return $app->json($result);
        })
            ->bind('post-api-product-insert')
        ;

        $controllers->put('/product/{id}', function(Request $request, $id) use($app){

            $result = $app['productService']->update(
                [
                    'id' => (int) $id,
                    'name' => $request->request->get('name'),
                    'description' => $request->request->get('description'),
                    'value' => $request->request->get('value')
                ]
            );

            return $app->json($result);
        })
            ->bind('post-api-product-update')
        ;

        $controllers->delete('/product/{id}', function($id) use($app){

            $result = $app['productService']->delete((int) $id);

            return $app->json($result);
        })
            ->bind('post-api-product-delete')
        ;

        return $controllers;
    }


}


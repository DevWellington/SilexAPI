<?php

namespace DevWellington\Shop\Controllers;

use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class ApiControllerProvider implements ControllerProviderInterface
{
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

            $data = [
                'name' => $request->request->get('name'),
                'description' => $request->request->get('description'),
                'value' => $request->request->get('value')
            ];

            $isValid = $app['validator']->validate($app['productService']->validate($data));

            $returnValidate['status'] = 'error';
            if (count($isValid) > 0){
                foreach ($isValid as $noValids) {
                    $returnValidate['fieldsError'][$noValids->getPropertyPath()] = $noValids->getMessage();
                }

                return $app->json($returnValidate);
            }

            $resultData = $app['productService']->insert($data);

            $result['status'] = ($resultData !== false) ? 'success' : ($resultData !== false);
            $result['data'] = $resultData;

            return $app->json($result);
        })
            ->bind('post-api-product-insert')
        ;

        $controllers->put('/product/{id}', function(Request $request, $id) use($app){

            $data = [
                'id' => (int) $request->request->get('id'),
                'name' => $request->request->get('name'),
                'description' => $request->request->get('description'),
                'value' => $request->request->get('value')
            ];

            $isValid = $app['validator']->validate($app['productService']->validate($data));

            $returnValidate['status'] = 'error';
            if (count($isValid) > 0){
                foreach ($isValid as $noValids) {
                    $returnValidate['fieldsError'][$noValids->getPropertyPath()] = $noValids->getMessage();
                }

                return $app->json($returnValidate);
            }

            $resultData = $app['productService']->update($data);

            $result['status'] = ($resultData !== false) ? 'success' : ($resultData !== false);
            $result['data'] = $resultData;

            return $app->json($result);
        })
            ->bind('post-api-product-update')
        ;

        $controllers->delete('/product/{id}', function($id) use($app){

            $resultData  = $app['productService']->delete((int) $id);

            $result['status'] = ($resultData !== false) ? 'success' : ($resultData !== false);
            $result['data'] = $resultData;

            return $app->json($result);
        })
            ->bind('post-api-product-delete')
        ;

        return $controllers;
    }


}


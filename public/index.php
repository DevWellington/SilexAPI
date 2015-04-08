<?php

require_once __DIR__.'/../src/bootstrap.php';

use \DevWellington\Shop\Entity\ProductEntity;
use \DevWellington\Shop\Mapper\ProductMapper;
use \Symfony\Component\HttpFoundation\Request;
use \DevWellington\Shop\Controllers\ApiControllerProvider;

$app['productService'] = function() use($app)
{
    $productEntity = new ProductEntity();
    $productMapper = new ProductMapper($app['pdo']);

    return new \DevWellington\Shop\Service\ProductService($productEntity, $productMapper);
};

$app->get('/', function() use($app){
    return $app->redirect($app['url_generator']->generate('get-products'));
});


$app->get('/products/', function() use($app){

    $products = $app['productService']->fetchAll();

    $msg = [
        'status' => $app['session']->get('msg_status'),
        'description' => $app['session']->get('msg_description')
    ];

    $app['session']->remove('msg_status');
    $app['session']->remove('msg_description');

    return $app['twig']->render('products/list.twig',
        array(
            'products' => $products,
            'msg' => $msg,
        )
    );
})
    ->bind('get-products')
;

$app->get('/products/edit/{id}', function($id) use($app){

    $product = $app['productService']->fetch($id);

    return $app['twig']->render('products/edit.twig', array(
        'product' => $product
    ));
})
    ->bind('get-products-edit')
;

$app->get('/products/insert/', function() use($app){

    return $app['twig']->render('products/insert.twig', array());
})
    ->bind('get-products-insert')
;

// todo: Refactor to API
$app->get('/app/product/delete/{id}', function($id) use ($app){

    $return = $app['productService']->delete((int) $id);
    $app['session']->set('msg_status', ! $return === false);
    $app['session']->set('msg_description', 'Removido');

    return $app->redirect($app['url_generator']->generate('get-products'));
})
    ->bind('app-product-delete')
;

$app->post('/app/product/', function(Request $request) use ($app){

    $return = $app['productService']->insert(
        [
            'name' => $request->request->get('name'),
            'description' => $request->request->get('description'),
            'value' => $request->request->get('value')
        ]
    );

    $app['session']->set('msg_status', ! $return === false);
    $app['session']->set('msg_description', 'Inserido');

    return $app->redirect($app['url_generator']->generate('get-products'));
})
    ->bind('app-product-insert')
;


$app->put('/app/product/', function(Request $request) use ($app){

    $return = $app['productService']->update(
        [
            'id' => (int) $request->request->get('id'),
            'name' => $request->request->get('name'),
            'description' => $request->request->get('description'),
            'value' => $request->request->get('value')
        ]
    );

    $app['session']->set('msg_status', ! $return === false);
    $app['session']->set('msg_description', 'Alterado');

    return $app->redirect($app['url_generator']->generate('get-products'));
})
    ->bind('app-product-update')
;

$app->mount('/api', new ApiControllerProvider());

$app->run();
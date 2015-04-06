<?php

require_once __DIR__.'/../src/bootstrap.php';

use \DevWellington\Shop\Entity\ProductEntity;
use \DevWellington\Shop\Mapper\ProductMapper;
use \Symfony\Component\HttpFoundation\Request;

$app['productService'] = function() use($app)
{
    $productEntity = new ProductEntity();
    $productMapper = new ProductMapper($app['pdo']);

    return new \DevWellington\Shop\Service\ProductService($productEntity, $productMapper);
};

$host = filter_var("http://".$_SERVER["HTTP_HOST"], FILTER_SANITIZE_URL);

$app->get('/', function() use($host){

    $result = 'curl -X POST -H "Cache-Control: no-cache" -H "Content-Type: multipart/form-data;" -F "name=Notebook" -F "description=Notebook Dell Inspiron 1545" -F "value=1300" '.$host.'/insert/product<br /><br />';
    $result .= 'curl -X POST -H "Cache-Control: no-cache" -H "Content-Type: multipart/form-data;" -F "name=Notebook HP" -F "description=Notebook HP Core i5" -F "value=2000" '.$host.'/update/product/1<br /><br />';
    $result .= 'curl -X POST -H "Cache-Control: no-cache" -H "Content-Type: multipart/form-data;" -F "name=Notebook HP" -F "description=Notebook HP Core i5" -F "value=2000" '.$host.'/delete/product/1<br /><br />';

    return $result;
});

$app->post('/insert/product', function(Request $request) use($app){

    $result = $app['productService']->insert(
        [
            'name' => $request->request->get('name'),
            'description' => $request->request->get('description'),
            'value' => $request->request->get('value')
        ]
    );

    return $app->json($result);
});

$app->post('/update/product/{id}', function(Request $request, $id) use($app){

    $result = $app['productService']->update(
        [
            'id' => (int) $id,
            'name' => $request->request->get('name'),
            'description' => $request->request->get('description'),
            'value' => $request->request->get('value')
        ]
    );

    return $app->json($result);
});

$app->post('/delete/product/{id}', function($id) use($app){

    $result = $app['productService']->delete((int) $id);

    return $app->json($result);
});

$app->run();

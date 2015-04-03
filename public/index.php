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

$app->get('/', function(){
    $result = 'curl -X POST -H "Cache-Control: no-cache" -H "Postman-Token: a0ccc891-62c6-8ef2-633c-65977ae259f5" -H "Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW" -F "name=Notebook" -F "description=Notebook Dell Inspiron 1545" -F "value=1300" http://localhost:8080/insert/product<br /><br />';
    $result .= 'curl -X POST -H "Cache-Control: no-cache" -H "Postman-Token: bb68e03c-cefc-9001-1117-02deace2306e" -H "Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW" -F "name=Notebook HP" -F "description=Notebook HP Core i5" -F "value=2000" http://localhost:8080/update/product/1<br /><br />';
    $result .= 'curl -X POST -H "Cache-Control: no-cache" -H "Postman-Token: c84f0f6e-9288-41d5-2d90-45ebb7bfabdd" -H "Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW" -F "name=Notebook HP" -F "description=Notebook HP Core i5" -F "value=2000" http://localhost:8080/delete/product/1<br /><br />';

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
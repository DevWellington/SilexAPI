<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new \Silex\Application();
$app['debug'] = true;

$app->get('/clientes', function() use($app){

    $clients = [
        [
            'nome' => 'Wellington Ribeiro',
            'email' => 'DevWellington@gmail.com',
            'cpf' => '000.000.000-00'
        ],
        [
            'nome' => 'Joao Jose',
            'email' => 'joao@jose.com',
            'cpf' => '111.111.111-11'
        ],
        [
            'nome' => 'Jose Maria',
            'email' => 'joao@maria.com',
            'cpf' => '222.222.222-22'
        ]

    ];

    return $app->json($clients);
});

$app->run();
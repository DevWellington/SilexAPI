<?php

require_once __DIR__.'/../vendor/autoload.php';

define("DBNAME", __DIR__."/../src/db.sqlite");

$app = new \Silex\Application();
$app['debug'] = true;

$app['pdo'] = new \PDO("sqlite:".DBNAME);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());
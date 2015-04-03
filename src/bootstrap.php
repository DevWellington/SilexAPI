<?php

require_once __DIR__.'/../vendor/autoload.php';

define(DBNAME, __DIR__."/../src/db.sqlite");

$app = new \Silex\Application();
$app['debug'] = true;

$app['pdo'] = new \PDO("sqlite:".DBNAME);

<?php

use SONFin\Application;
use SONFin\Plugins\DbPlugin;
use SONFin\ServiceContainer;
use SONFin\Plugins\ViewPlugin;
use SONFin\Plugins\RoutePlugin;


require_once __DIR__ . "/../vendor/autoload.php";

// carrega container de serviços
$serviceContainer = new ServiceContainer();

// cria instancia da aplicação
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());

require_once __DIR__ . '/../src/Controllers/CategoryCost.php';
require_once __DIR__ . '/../src/Controllers/Users.php';

/* $app->get('/', function (RequestInterface $request) {
    var_dump($request->getUri());
    die();
    echo "Hello World!!";
}); */

/*
$app->get('/home/{name}', function (ServerRequestInterface $request) {
    echo "Mostrando pagina Home </br>";
    echo $request->getAttribute('name');
});
 */
$app->start();

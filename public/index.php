<?php

use SONFin\Application;
use SONFin\Plugins\DbPlugin;
use SONFin\ServiceContainer;
use Zend\Diactoros\Response;
use SONFin\Plugins\ViewPlugin;
use SONFin\Models\CategoryCost;
use SONFin\Plugins\RoutePlugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . "/../vendor/autoload.php";

// carrega container de serviços
$serviceContainer = new ServiceContainer();

// cria instancia da aplicação
$app = new Application($serviceContainer);

$app->plugin(new RoutePlugin());
$app->plugin(new ViewPlugin());
$app->plugin(new DbPlugin());


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
$app->get('/', function (RequestInterface $request) use ($app) {
    $view = $app->service('view.renderer');
    return $view->render('teste.html.twig', ['name' => 'Edson CJR']);
});

$app->get(
    '/category-costs',
    function () use ($app) {
        $view = $app->service('view.renderer');
        $categoryModel = new CategoryCost();
        $categorias = $categoryModel->all();
        return $view->render(
            'category-costs/list.html.twig',
            [
                'categorias' => $categorias
            ]
        );
    }
);



/* $app->get('/home/{name}', function (ServerRequestInterface $request) {
    $response = new Response();
    $response->getBody()->write("Response com emitter do Diactoros");

    return $response;
}); */


$app->start();

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
use Zend\Diactoros\Response\RedirectResponse;

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
$app->get(
    '/',
    function (RequestInterface $request) use ($app) {
        $view = $app->service('view.renderer');
        return $view->render('teste.html.twig', ['name' => 'Edson CJR']);
    }
);

$app
    ->get(
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
        },
        'category-costs.list'
    )
->get(
    '/category-costs/new',
    function () use ($app) {
        $view = $app->service('view.renderer');

        return $view->render(
            'category-costs/create.html.twig'
        );
    },
    'category-costs.new'
)
    ->get(
        '/category-costs/{id}/edit',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = $request->getAttribute('id');
            $category = CategoryCost::findOrFail($id);

            return $view->render(
                'category-costs/edit.html.twig',
                [
                    'categoria' => $category
                ]
            );
        },
        'category-costs.edit'
    )
    ->get(
        '/category-costs/{id}/show',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = $request->getAttribute('id');
            $category = CategoryCost::findOrFail($id);

            return $view->render(
                'category-costs/show.html.twig',
                [
                    'categoria' => $category
                ]
            );
        },
        'category-costs.show'
    )
    ->post(
        '/category-costs/{id}/update',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = $request->getAttribute('id');

            /**
             * @var CategoryCost $category
             */
            $category = CategoryCost::findOrFail($id);
            $data = $request->getParsedBody();

            $category->fill($data); // atualiza a consulta com o dado form - atribuição massiva

            $category->save();

            return $app->route('category-costs.list');
        },
        'category-costs.update'
    )
    ->get(
        '/category-costs/{id}/delete',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = $request->getAttribute('id');

            /**
             * @var CategoryCost $category
             */
            $category = CategoryCost::findOrFail($id);
            $category->delete();

            return $app->route('category-costs.list');
        },
        'category-costs.delete'
    )
    ->post(
        '/category-costs/store',
        function (ServerRequestInterface $request) use ($app) {
            // cadastro de categorias
        

            $data = $request->getParsedBody();
            CategoryCost::create($data);
            //após inserir redireciona para a pagina de lista categorias
            return $app->route('category-costs.list');
        },
        'category-costs.store'
    );

/* $app->get('/home/{name}', function (ServerRequestInterface $request) {
    $response = new Response();
    $response->getBody()->write("Response com emitter do Diactoros");

    return $response;
}); */


$app->start();

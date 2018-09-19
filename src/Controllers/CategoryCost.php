<?php

use Zend\Diactoros\Response;
use SONFin\Models\CategoryCost;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\RedirectResponse;

$app->get(
    '/',
    function (RequestInterface $request) use ($app) {
        $view = $app->service('view.renderer');
        return $view->render('teste.html.twig', ['name' => 'Edson CJR']);
    }
)
->get(
    '/category-costs',
    function () use ($app) {
        $view = $app->service('view.renderer');
        $repository = $app->service('category-cost.repository');
        $auth = $app->service('auth');

        /*
        $categoryModel = new CategoryCost();
        $categorias = $categoryModel->all();
         */
        $categorias = $repository->findByField('user_id', $auth->user()->getId());
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
            $repository = $app->service('category-cost.repository');

            $auth = $app->service('auth');
            
            $category = $repository->findOneBy(
                [
                'id' => $id,
                'user_id' => $auth->user()->getId()
                ]
            );

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
            $repository = $app->service('category-cost.repository');

            $auth = $app->service('auth');
            
            $category = $repository->findOneBy(
                [
                'id' => $id,
                'user_id' => $auth->user()->getId()
                ]
            );

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
            $repository = $app->service('category-cost.repository');


            /**
             * @var CategoryCost $category
             */
            $data = $request->getParsedBody();
            /*
                        $category->fill($data); // atualiza a consulta com o dado form - atribuição massiva

                        $category->save();
             */
            $auth = $app->service('auth');
            $data = $request->getParsedBody();
            $data['user_id'] = $auth->user()->getId();
            $repository->update(
                [
                'id' => $id,
                'user_id' => $auth->user()->getId()
                ], $data
            );
            return $app->route('category-costs.list');
        },
        'category-costs.update'
    )
    ->get(
        '/category-costs/{id}/delete',
        function (ServerRequestInterface $request) use ($app) {
            
            $view = $app->service('view.renderer');
            $id = $request->getAttribute('id');
            $repository = $app->service('category-cost.repository');

            $auth = $app->service('auth');
            /**
             * @var CategoryCost $category
             */
            $repository->delete(
                [
                'id' => $id,
                'user_id' => $auth->user()->getId()
                ]
            );

            return $app->route('category-costs.list');
        },
        'category-costs.delete'
    )
    ->post(
        '/category-costs/store',
        function (ServerRequestInterface $request) use ($app) {
            // cadastro de categorias

            $auth = $app->service('auth');

            $data = $request->getParsedBody();
            $data['user_id'] = $auth->user()->getId();


            $repository = $app->service('category-cost.repository');

            $repository->create($data);
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

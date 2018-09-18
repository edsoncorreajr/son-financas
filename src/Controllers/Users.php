<?php
declare (strict_types = 1);
use Zend\Diactoros\Response;
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
    '/users',
    function () use ($app) {
        $view = $app->service('view.renderer');
        $repository = $app->service('user.repository');

        $usuarios = $repository->all();
        return $view->render(
            'users/list.html.twig',
            [
                    'usuarios' => $usuarios
                ]
        );
    },
    'users.list'
)
    ->get(
        '/users/new',
        function () use ($app) {
            $view = $app->service('view.renderer');

            return $view->render(
                'users/create.html.twig'
            );
        },
        'users.new'
    )
    ->get(
        '/users/{id}/edit',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = (int) $request->getAttribute('id');
            $repository = $app->service('user.repository');

            $user = $repository->find($id);

            return $view->render(
                'users/edit.html.twig',
                [
                    'usuario' => $user
                ]
            );
        },
        'users.edit'
    )
    ->get(
        '/users/{id}/show',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = (int) $request->getAttribute('id');
            $repository = $app->service('user.repository');

            $user = $repository->find($id);

            return $view->render(
                'users/show.html.twig',
                [
                    'usuario' => $user
                ]
            );
        },
        'users.show'
    )
    ->post(
        '/users/{id}/update',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = (int) $request->getAttribute('id');
            $repository = $app->service('user.repository');

            $data = $request->getParsedBody();

            $repository->update($id, $data);
            return $app->route('users.list');
        },
        'users.update'
    )
    ->get(
        '/users/{id}/delete',
        function (ServerRequestInterface $request) use ($app) {
            $view = $app->service('view.renderer');
            $id = (int) $request->getAttribute('id');
            $repository = $app->service('user.repository');


            /**
             * @var Users $user
             */
            $user = $repository->find($id);
            $user->delete();

            return $app->route('users.list');
        },
        'users.delete'
    )
    ->post(
        '/users/store',
        function (ServerRequestInterface $request) use ($app) {
            // cadastro de usuarios


            $data = $request->getParsedBody();
            $repository = $app->service('user.repository');

            $repository->create($data);
            //após inserir redireciona para a pagina de lista usuarios
            return $app->route('users.list');
        },
        'users.store'
    );

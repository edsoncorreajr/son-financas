<?php
declare(strict_types = 1);

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

$app->get(
    '/login',
    function (RequestInterface $request) use ($app) {
        $view = $app->service('view.renderer');
        return $view->render('auth/login.html.twig');
    },
    'auth.show_loginform'
)
    ->post(
        '/login',
        function (ServerRequestInterface $request) use ($app) {
            /* $view = $app->service('view.renderer');
            $id = $request->getAttribute('id');
            $repository = $app->service('user.repository');

            $data = $request->getParsedBody();

            $repository->update($id, $data);
            return $app->route('users.list'); */
        },
        'auth.login'
    );

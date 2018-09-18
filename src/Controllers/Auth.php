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
    'auth.show_login_form'
)
    ->post(
        '/login',
        function (ServerRequestInterface $request) use ($app) {
            $auth = $app->service('auth');

            $data = $request->getParsedBody();
            $result = $auth->login($data);
            $view = $app->service('view.renderer');

            if (!$result) {
                return $view->render('auth/login.html.twig');
            }
            return $app->route('category-costs.list');
        },
        'auth.login'
    )
    ->get(
        '/logout',
        function (RequestInterface $request) use ($app) {
            $app->service('auth')->logout();
            return $app->route('auth.show_login_form');
        },
        'auth.logout'
    );

<?php
declare(strict_types=1);

namespace SONFin\Plugins;

use Aura\Router\RouterContainer;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

use SONFin\ServiceContainerInterface;
use Zend\Diactoros\ServerRequestFactory;

class RoutePlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $routerContainer = new RouterContainer();
        /* Registrar as rotas da aplicação        */
        $map = $routerContainer->getMap();
        /* identifica a rota que está sendo acessada        */
        $matcher = $routerContainer->getMatcher();

        /* gera links com base nas rotas registradas       */
        $generator = $routerContainer->getGenerator();

        /**
         * @var RequestInterface $request
        */
        $request = $this->getRequest();

        /* registra os serviços relativos da rota       */
        $container->add('routing', $map);
        $container->add('routing.matcher', $matcher);
        $container->add('routing.generator', $generator);

        /** registra o serviço para request usando a string Psr\Http\Message\RequestInterface
         * guarda a requisição do request
         */
        $container->add(RequestInterface::class, $request);

        /**
         * cria o serviço armazenado em route que recebe as rotas geradas
         * o segundo parametro em lazzy (será executado quando o serviço for chamado)
         * é uma função que realiza a logica do serviço
         * quando addLazy a função recebe como parametro o próprio container
         * function cria a rota do serviço
         */

        $container->addLazy(
            'route',
            function (ContainerInterface $container) {
                $matcher = $container->get('routing.matcher');
                
                /**
                * @var RequestInterface $request
                */
                $request = $container->get(RequestInterface::class);

                /** Returna matcher combinado com a request gerada = rota para acessar */
                return $matcher->match($request);
            }
        );
    }

    protected function getRequest(): RequestInterface
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    }
}

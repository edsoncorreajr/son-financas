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
    public function register(ServiceContainerInterface $container){
        
        $routerContainer = new RouterContainer();
        /* Registrar as rotas da aplicação        */
        $map = $routerContainer->getMap();
        /* identifica a rota que está sendo acessada        */
        $matcher = $routerContainer->getMatcher();

        /* gera links com base nas rotas registradas       */
        $generator = $routerContainer->getGenerator();
        $request = $this->getRequest();

        $container->add('routing', $map);
        $container->add('routing.matcher', $matcher);
        $container->add('routing.generator', $generator);

        /* registra o serviço usando a string Psr\Http\Message\RequestInterface */
        $container->add(RequestInterface::class, $request);

        /* quando addLazy a função recebe como parametro o próprio container */

        $container->addLazy('route', function(ContainerInterface $container){
            $matcher = $container->get('routing.matcher');
            $request = $container->get(RequestInterface::class);
            return $matcher->match($request);
        });

    }

    protected function getRequest(): RequestInterface 
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
    }
}

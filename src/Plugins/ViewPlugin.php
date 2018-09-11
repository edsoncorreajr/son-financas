<?php

namespace SONFin\Plugins;

use SONFin\View\ViewRenderer;
use SONFin\Plugins\PluginInterface;
use SONFin\ServiceContainerInterface;
use Interop\Container\ContainerInterface;

class ViewPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy(
            'twig',
            function (ContainerInterface $container) {
                $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../templates');
                $twig = new \Twig_Environment($loader);
                return $twig;
            }
        );

        $container->addLazy(
            'view.renderer',
            function (ContainerInterface $container) {
                $twigEnvironment = $container->get('twig');
                return new ViewRenderer($twigEnvironment);
            }
        );
    }
}

<?php

namespace SONFin\Plugins;

use SONFin\Auth\Auth;
use SONFin\ServiceContainerInterface;

class AuthPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy(
            'auth.repository',
            function (ContainerInterface $container) {
                return new Auth();
            }
        );
    }
}

<?php

namespace SONFin\Plugins;

use SONFin\View\ViewRenderer;
use SONFin\Plugins\PluginInterface;
use SONFin\ServiceContainerInterface;
use Interop\Container\ContainerInterface;
use SONFin\View\Twig\TwigGlobals;

class ViewPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy(
            'twig',
            function (ContainerInterface $container) {
                $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../templates');
                $twig = new \Twig_Environment($loader);

                /** 
                 * @var \SONFin\Auth\Auth
                 */
                $auth = $container->get('auth');

                $generator = $container->get('routing.generator');

                /** 
                 * Adiciona a extensão personalisada no twig, a qual insere no template
                 * uma varivel global, neste caso credenciais da autenticação
                 */
                $twig->addExtension(new TwigGlobals($auth));
                $twig->addFunction(
                    new \Twig_SimpleFunction(
                        'route',
                        function (string $name, array $params = []) use ($generator) {
                            return $generator->generate($name, $params);
                        }
                    )
                );

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

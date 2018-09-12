<?php
declare(strict_types=1);

namespace SONFin;

use SONFin\Plugins\PluginInterface;
use SONFin\ServiceContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response\RedirectResponse;

class Application
{
    private $serviceContainer;

    /**
     * Application constructor
     *
     * @param $serviceContainer
     **/
    public function __construct(ServiceContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Função que recupera serviço
     */
    public function service($name)
    {
        return $this->serviceContainer->get($name);
    }

    /**
     * Função para adicionar service
     */
    public function addService(string $name, $service): void
    {
        if (is_callable($service)) {
            $this->serviceContainer->addLazzy($name, $service);
        } else {
            $this->serviceContainer->add($name, $service);
        }
    }

    /** Usado para registrar um plugin */
    public function plugin(PluginInterface $plugin): void
    {
        $plugin->register($this->serviceContainer);
    }

    public function get($path, $action, $name= null): Application
    {
        $routing = $this->service('routing');
        $routing->get($name, $path, $action);
        return $this;
    }

    public function post($path, $action, $name = null) : Application
    {
        $routing = $this->service('routing');
        $routing->post($name, $path, $action);
        return $this;
    }
    public function redirect($path): RedirectResponse 
    {
        return new RedirectResponse($path);
    }

    protected function emitResponse(ResponseInterface $response)
    {
        /** 
         * @var SapiEmitter $emitter
         */
        $emitter = new SapiEmitter();
        $emitter->emit($response);
        
    }

    /** 
     * cria função para realizar o roteamento
     * @var string $name : recebe o nome para a rota
     * @var array $params : padrão null, recebe os parametros atribuidos para a rota
     * @return RedirectResponse
     */
    public function route(string $name, array $params = []){
        $generator = $this->service('routing.generator');
        $path = $generator->generate($name, $params);
        return $this->redirect($path);
    }

    public function start()
    {
        /* pega a rota enviada pelo usuario  */
        $route = $this->service('route');

        /** @var RequestInterface $request */
        $request = $this->service(RequestInterface::class);

        if (!$route) {
            echo "Página não encontrada";
            exit;
        }

        foreach ($route->attributes as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }

        // captura a ação da rota chamada
        $callable = $route->handler;

        // response chama a ação da rota
        /** 
         * @var ResponseInterface $request 
         */
        $response = $callable($request);

        // emite a resposta
        $this->emitResponse($response);
    }
}

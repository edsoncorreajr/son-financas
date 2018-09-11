<?php
declare(strict_types=1);


namespace SONFin\View;

use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

class ViewRenderer implements ViewRendererInterface
{
    /** 
     * @var \Twig_Environment $twigEnvironment
     */
    private $twigEnvironment;

    public function __construct(\Twig_Environment $twigEnvironment){
        $this->twigEnvironment = $twigEnvironment;
        
    }

    public function render(string $template, array $context = []): ResponseInterface
    {
        $result = $this->twigEnvironment->render($template, $context);
        $response = new Response();
        $response->getBody()->write($result);
        return $response;

    }
}
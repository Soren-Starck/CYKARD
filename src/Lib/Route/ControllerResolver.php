<?php

namespace App\Lib\Route;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;

class ControllerResolver extends BaseControllerResolver
{
    private ContainerInterface $container;
    private LoggerInterface $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        parent::__construct();
        $this->container = $container;
        $this->logger = $logger;
    }

    protected function instantiateController($class): object
    {
        $this->logger->info('Instantiating controller', ['class' => $class]);
        return $this->container->get($class);
    }
}
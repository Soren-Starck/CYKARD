<?php


require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
try {
    $loader->load('config/services.yaml');
} catch (Exception $e) {
    echo $e->getMessage();
    exit;
}

$serviceIds = $containerBuilder->getServiceIds();

var_dump($serviceIds);
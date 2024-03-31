<?php

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function() {
    \App\Controller\Route\RouteurURL::traiterRequete();
};
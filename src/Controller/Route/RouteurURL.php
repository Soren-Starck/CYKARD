<?php

namespace App\Controller\Route;

use App\Controller\GeneriqueController;
use App\Lib\Route\AttributeRouteControllerLoader;
use App\Lib\Route\Conteneur;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class RouteurURL
{

    public static function traiterRequete()
    {
        $twigLoader = new FilesystemLoader(dirname(__DIR__).'/../templates');
        $twig = new Environment(
            $twigLoader,
            [
                'autoescape' => 'html',
                'strict_variables' => true
            ]
        );
        Conteneur::addService("twig", $twig);
        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        Conteneur::addService('serializer', $serializer);
        $requete = Request::createFromGlobals();

        $fileLocator = new FileLocator(dirname(__DIR__));
        $attrClassLoader = new AttributeRouteControllerLoader();
        $routes = (new AttributeDirectoryLoader($fileLocator, $attrClassLoader))->load(dirname(__DIR__));

        $contexteRequete = (new RequestContext())->fromRequest($requete);
        $generateurUrl = new UrlGenerator($routes, $contexteRequete);
        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);
        Conteneur::addService("generateurUrl", $generateurUrl);
        Conteneur::addService("assistantUrl", $assistantUrl);
        try{

            $associateurUrl = new UrlMatcher($routes, $contexteRequete);
            $donneesRoute = $associateurUrl->match($requete->getPathInfo());

            $requete->attributes->add($donneesRoute);

            $resolveurDeControleur = new ControllerResolver();
            $controleur = $resolveurDeControleur->getController($requete);

            $resolveurDArguments = new ArgumentResolver();
            $arguments = $resolveurDArguments->getArguments($requete, $controleur);
            $response = call_user_func_array($controleur, $arguments);
        } catch (BadRequestHttpException $exception) {
            $response = GeneriqueController::renderError($exception->getMessage(), 400);
        } catch (MethodNotAllowedHttpException $exception) {
            $response = GeneriqueController::renderError($exception->getMessage(), 405);
        } catch (ResourceNotFoundException|NotFoundHttpException $exception) {
            $response = GeneriqueController::renderError($exception->getMessage(), 404);
        } catch (AccessDeniedHttpException $exception) {
            $response = GeneriqueController::renderError($exception->getMessage(), 403);
        } catch (ServiceUnavailableHttpException $exception) {
            $response = GeneriqueController::renderError($exception->getMessage(), 503);
        } catch (\Exception $exception) {
            $response = GeneriqueController::renderError($exception->getMessage());
        }
        $response->send();
    }
}
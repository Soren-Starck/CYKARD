<?php

namespace App\Controller\Route;

use App\Controller\GeneriqueController;
use App\Lib\Flash\MessageFlash;
use App\Lib\Route\AttributeRouteControllerLoader;
use App\Lib\Route\Conteneur;
use App\Lib\Security\UserConnection\UserHelper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
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
use Twig\TwigFunction;

class RouteurURL
{

    /**
     * @throws \Exception
     */
    public static function traiterRequete(): void
    {
        $configDir = realpath(__DIR__ . '/../../../config');
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader($container, new FileLocator($configDir));
        $loader->load('conteneur.yaml');


        $twigLoader = new FilesystemLoader(dirname(__DIR__) . '/../templates');
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
        $contexteRequete = (new RequestContext())->fromRequest($requete);

        $fileLocator = new FileLocator(__DIR__);
        $attrClassLoader = new AttributeRouteControllerLoader();
        $routes = (new AttributeDirectoryLoader($fileLocator, $attrClassLoader))->load(__DIR__);

        $generateurUrl = new UrlGenerator($routes, $contexteRequete);
        $assistantUrl = new UrlHelper(new RequestStack(), $contexteRequete);

        $functions = [
            new TwigFunction('is_user_logged_in', [UserHelper::class, 'isUserLoggedIn']),
            new TwigFunction('does_user_have_role', [UserHelper::class, 'doesUserHaveRole']),
            new TwigFunction('encore_entry_link_tags', function (string $entryName) {
                return '<link rel="stylesheet" href="/build/' . $entryName . '.css">';
            }),
            new TwigFunction('encore_entry_script_tags', function (string $entryName) {
                return '<script src="/build/' . $entryName . '.js"></script>';
            }),
            new TwigFunction("path", function ($nomRoute, $parametres = []) use ($generateurUrl) {
                return $generateurUrl->generate($nomRoute, $parametres);
            }),
            new TwigFunction("asset", function ($chemin) use ($assistantUrl) {
                return $assistantUrl->getAbsoluteUrl($chemin);
            }),
        ];

        foreach ($functions as $function) {
            $twig->addFunction($function);
        }

        $twig->addGlobal("app", [
            "flashes" => MessageFlash::lireTousMessages(),
            "user" => UserHelper::isUserLoggedIn() ? UserHelper::getLoginUtilisateurConnecte() : null
        ]);

        Conteneur::addService("generateurUrl", $generateurUrl);
        Conteneur::addService("assistantUrl", $assistantUrl);

        try {
            $associateurUrl = new UrlMatcher($routes, $contexteRequete);
            $donneesRoute = $associateurUrl->match($requete->getPathInfo());

            $requete->attributes->add($donneesRoute);

            $resolveurDeControleur = new ControllerResolver();
            $controleur = $resolveurDeControleur->getController($requete);

            $resolveurDArguments = new ArgumentResolver();
            $arguments = $resolveurDArguments->getArguments($requete, $controleur);
            $response = call_user_func_array($controleur, $arguments);
        } catch (BadRequestHttpException $exception) {
            $generiqueController = new GeneriqueController(new Conteneur());
            $response = $generiqueController->renderError($exception->getMessage(), 400);
        } catch (MethodNotAllowedHttpException $exception) {
            $generiqueController = new GeneriqueController(new Conteneur());
            $response = $generiqueController->renderError($exception->getMessage(), 405);
        } catch (ResourceNotFoundException|NotFoundHttpException $exception) {
            $generiqueController = new GeneriqueController(new Conteneur());
            $response = $generiqueController->renderError($exception->getMessage(), 404);
        } catch (AccessDeniedHttpException $exception) {
            $generiqueController = new GeneriqueController(new Conteneur());
            $response = $generiqueController->renderError($exception->getMessage(), 403);
        } catch (ServiceUnavailableHttpException $exception) {
            $generiqueController = new GeneriqueController(new Conteneur());
            $response = $generiqueController->renderError($exception->getMessage(), 503);
        } catch (\Exception $exception) {
            $generiqueController = new GeneriqueController(new Conteneur());
            $response = $generiqueController->renderError($exception->getMessage());
        }
        $response->send();
    }
}

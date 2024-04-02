<?php

namespace App\Controller;

use App\Lib\Flash\MessageFlash;
use App\Lib\Route\AttributeRouteControllerLoader;
use App\Lib\Security\UserConnection\UserHelper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\AttributeDirectoryLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Twig\TwigFunction;

class RouteurURL
{
    /**
     * @throws \Exception
     */
    public static function traiterRequete(): void
    {

        $conteneur = new ContainerBuilder();
        $conteneur->setParameter('project_root', dirname(__DIR__).'/..');
        $conteneur->set('container', $conteneur);
        $loader = new YamlFileLoader($conteneur, new FileLocator(dirname(__DIR__) . '/../config'));
        $loader->load('conteneur.yaml');

        $twig = $conteneur->get('twig');

        $requester = Request::createFromGlobals();

        $fileLocator = new FileLocator(__DIR__);
        $attrClassLoader = new AttributeRouteControllerLoader();
        $routes = (new AttributeDirectoryLoader($fileLocator, $attrClassLoader))->load(__DIR__);

        $contexteRequester = new RequestContext();
        $contexteRequester->fromRequest($requester);

        $conteneur->set('request_context', $contexteRequester);
        $conteneur->set('routes', $routes);
        $generateurUrl = $conteneur->get('url_generator');
        $assistantUrl = $conteneur->get('url_helper');

        $twig->addFunction(new TwigFunction("route", function ($nomRoute, $parametres = []) use ($generateurUrl) {return $generateurUrl->generate($nomRoute, $parametres);}));
        $twig->addFunction(new TwigFunction("asset", function ($chemin) use ($assistantUrl) {return $assistantUrl->getAbsoluteUrl($chemin);}));
        $twig->addFunction(new TwigFunction("path", function ($nomRoute, $parametres = []) use ($generateurUrl) {return $generateurUrl->generate($nomRoute, $parametres);}));
        $twig->addFunction(new TwigFunction("is_user_logged_in", [UserHelper::class, 'isUserLoggedIn']));
        $twig->addFunction(new TwigFunction("does_user_have_role", [UserHelper::class, 'doesUserHaveRole']));
        $twig->addFunction(new TwigFunction("encore_entry_link_tags", function (string $entryName) {return '<link rel="stylesheet" href="/build/' . $entryName . '.css">';}));
        $twig->addFunction(new TwigFunction("encore_entry_script_tags", function (string $entryName) {return '<script src="/build/' . $entryName . '.js"></script>';}));

        $twig->addGlobal("app", ["flashes" => MessageFlash::lireTousMessages(), "user" => UserHelper::isUserLoggedIn() ? UserHelper::getLoginUtilisateurConnecte() : null]);
        $twig->addGlobal('utilisateur', UserHelper::getLoginUtilisateurConnecte());
        $twig->addGlobal('messagesFlash', new MessageFlash());

        try {
            $associateurUrl = new UrlMatcher($routes, $contexteRequester);
            $donneesRoute = $associateurUrl->match($requester->getPathInfo());

            $requester->attributes->add($donneesRoute);

            $resolveurDeControleur = new ContainerControllerResolver($conteneur);
            $controleur = $resolveurDeControleur->getController($requester);

            $resolveurDArguments = new ArgumentResolver();
            $arguments = $resolveurDArguments->getArguments($requester, $controleur);
            $response = call_user_func_array($controleur, $arguments);

        } catch (BadRequestHttpException $exception) {
            $response = $conteneur->get('controleur_generique')->renderError($exception->getMessage(), 400);
        } catch (MethodNotAllowedHttpException $exception) {
            $response = $conteneur->get('controleur_generique')->renderError($exception->getMessage(), 405);
        } catch (ResourceNotFoundException|NotFoundHttpException $exception) {
            $response = $conteneur->get('controleur_generique')->renderError($exception->getMessage(), 404);
        } catch (AccessDeniedHttpException $exception) {
            $response = $conteneur->get('controleur_generique')->renderError($exception->getMessage(), 403);
        } catch (ServiceUnavailableHttpException $exception) {
            $response = $conteneur->get('controleur_generique')->renderError($exception->getMessage(), 503);
        } catch (\Exception $exception) {
            $response = $conteneur->get('controleur_generique')->renderError($exception->getMessage());
        }
        $response->send();
    }
}
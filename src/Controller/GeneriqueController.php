<?php

namespace App\Controller;

use App\Lib\Route\Conteneur;
use App\Lib\Security\JWT\JsonWebToken;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

class GeneriqueController
{

    public function __construct(protected ContainerInterface $container){}

    protected function redirect(string $routeName = "", array $param = []): RedirectResponse
    {
        /** @var UrlGenerator $generateurUrl */
        $generateurUrl = $this->container->get("generateurUrl");
        return new RedirectResponse($generateurUrl->generate($routeName, $param));
    }

    public function renderError($messageErreur = "", $statusCode = 400): Response
    {
        $reponse = $this->renderTwig('erreur.html.twig', [
            "messageErreur" => $messageErreur
        ]);
        $reponse->setStatusCode($statusCode);
        return $reponse;
    }

    protected function renderTwig(string $cheminVue, array $parametres = []): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get("twig");
        $corpsReponse = $twig->render($cheminVue, $parametres);
        return new Response($corpsReponse);
    }

    public function getLoginFromJwt(Request $request): string
    {
        $jwt = $request->headers->get('Authorization');
        return JsonWebToken::getLogin($jwt);
    }

    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $serializer = $this->container->get('serializer');

        if ($serializer) {
            $json = $serializer->serialize($data, 'json', array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ], $context));

            return new JsonResponse($json, $status, $headers, true);
        }

        return new JsonResponse($data, $status, $headers);
    }
}
<?php

namespace App\Controller;

use App\Lib\Route\Conteneur;
use App\Service\I_ColonneService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class ColonneApiController extends GeneriqueController
{
    public function __construct(ContainerInterface $container, private I_ColonneService $colonneService)
    {
        parent::__construct($container);
    }

    #[Route('/api/colonne/{id}/modify', name: 'app_colonne_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->colonneService->modifyColonne($data, $this->getLoginFromCookieJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/colonne/{id}/delete', name: 'app_colonne_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $result = $this->colonneService->deleteColonne($this->getLoginFromCookieJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json(null, 204);
    }

    #[Route('/api/tableau/{tableau_id}/colonne', name: 'app_colonne_api_create', methods: ['POST'])]
    public function create(Request $request, int $tableau_id): Response
    {
        $login = $this->getLoginFromCookieJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->colonneService->createColonne($data, $login, $tableau_id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 201);
    }
}
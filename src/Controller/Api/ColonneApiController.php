<?php

namespace App\Controller\Api;

use App\Controller\GeneriqueController;
use App\Service\ColonneService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class ColonneApiController extends GeneriqueController
{
    private ColonneService $colonneService;

    public function __construct(ColonneService $colonneService)
    {
        $this->colonneService = $colonneService;
    }

    #[Route('/api/colonne/{id}/modify', name: 'app_colonne_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(Request $request, int $id): Response
    {
        $result = $this->colonneService->modifyColonne($request, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/colonne/{id}/delete', name: 'app_colonne_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $result = $this->colonneService->deleteColonne($request, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json(null, 204);
    }

    #[Route('/api/tableau/{tableau_id}/colonne', name: 'app_colonne_api_create', methods: ['POST'])]
    public function create(Request $request, int $tableau_id): Response
    {
        $result = $this->colonneService->createColonne($request, $tableau_id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 201);
    }
}
<?php

namespace App\Controller\Api;

use App\Controller\GeneriqueController;
use App\Service\CarteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class CarteApiController extends GeneriqueController
{

    private CarteService $carteService;

    public function __construct(CarteService $carteService)
    {
        $this->carteService = $carteService;
    }

    #[Route('/api/carte/{id}/modify', name: 'app_carte_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(Request $request, int $id): Response
    {
        $result = $this->carteService->modifyCarte($this->getLoginFromJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/carte/{id}', name: 'app_carte_api_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(Request $request, int $id): Response
    {
        $result = $this->carteService->showCarte($this->getLoginFromJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/carte/{id}/delete', name: 'app_carte_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $result = $this->carteService->deleteCarte($this->getLoginFromJwt($request), $id);
        if (isset($result['error'])) {
            return $this->json(['error' => $result['error']], $result['status']);
        }
        return $this->json(null, 204);
    }

    #[Route('/api/colonne/{colonne_id}/carte', name: 'app_carte_api_create', requirements: ['colonne_id' => Requirement::DIGITS], methods: ['POST'])]
    public function create(Request $request, int $colonne_id): Response
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->carteService->createCarte($data , $this->getLoginFromJwt($request), $colonne_id);
        if (isset($result['error'])) {
            return $this->json(['error' => $result['error']], $result['status']);
        }
        return $this->json($result, 201);
    }

}
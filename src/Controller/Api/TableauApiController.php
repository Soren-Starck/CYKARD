<?php

namespace App\Controller\Api;

use App\Controller\GeneriqueController;
use App\Service\TableauService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauApiController extends GeneriqueController
{
    private TableauService $tableauService;

    public function __construct(TableauService $tableauService)
    {
        $this->tableauService = $tableauService;
    }

    #[Route('/api/tableau/{id}/modify', name: 'app_tableau_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->tableauService->modifyTableau($data, $login, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/tableau/{id}', name: 'app_tableau_api_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(Request $request, int $id): Response
    {
        $result = $this->tableauService->showTableau($this->getLoginFromJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/tableau/{id}/delete', name: 'app_tableau_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $result = $this->tableauService->deleteTableau($this->getLoginFromJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json(null, 204);
    }

    #[Route('/api/tableau', name: 'app_tableau_api_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->tableauService->createTableau($data, $login);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 201);
    }

    #[Route('/api/tableau/join/{codetableau}', name: 'app_tableau_api_join', methods: ['POST'])]
    public function join(Request $request, string $codetableau): Response
    {
        $result = $this->tableauService->joinTableau($this->getLoginFromJwt($request), $codetableau);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 201);
    }
}
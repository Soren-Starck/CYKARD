<?php

namespace App\Controller;

use App\Service\I_CarteService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class CarteApiController extends GeneriqueController
{

    public function __construct(ContainerInterface $container, private I_CarteService $carteService)
    {
        parent::__construct($container);
    }

    #[Route('/api/carte/{id}/modify', name: 'app_carte_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(Request $request, int $id): Response
    {
        $result = $this->carteService->modifyCarte(json_decode($request->getContent(), true), $this->getLoginFromCookieJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/carte/{id}', name: 'app_carte_api_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(Request $request, int $id): Response
    {
        $result = $this->carteService->showCarte($this->getLoginFromCookieJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/carte/{id}/delete', name: 'app_carte_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $result = $this->carteService->deleteCarte($this->getLoginFromCookieJwt($request), $id);
        if (isset($result['error'])) {
            return $this->json(['error' => $result['error']], $result['status']);
        }
        return $this->json(null, 204);
    }

    #[Route('/api/colonne/{colonne_id}/carte', name: 'app_carte_api_create', requirements: ['colonne_id' => Requirement::DIGITS], methods: ['POST'])]
    public function create(Request $request, int $colonne_id): Response
    {
        $data = json_decode($request->getContent(), true);
        $result = $this->carteService->createCarte($data, $this->getLoginFromCookieJwt($request), $colonne_id);
        if (isset($result['error'])) {
            return $this->json(['error' => $result['error']], $result['status']);
        }
        return $this->json($result, 201);
    }

    #[Route('/api/carte/{id}/assign-user', name: 'app_carte_api_assign_user', requirements: ['id' => Requirement::DIGITS], methods: ['POST'])]
    public function assignUser(Request $request, int $id): Response
    {
        $data = json_decode($request->getContent(), true);
        $login = $data['userslogin'];
        $result = $this->carteService->assignUser($login, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/carte/{id}/unassign-user', name: 'app_carte_api_unassign_user', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function unassignUser(Request $request, int $id): Response
    {
        $result = $this->carteService->unassignUser($this->getLoginFromCookieJwt($request), $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

}

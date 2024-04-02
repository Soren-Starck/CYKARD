<?php

namespace App\Controller;

use App\Service\I_TableauService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class TableauApiController extends GeneriqueController
{

    public function __construct(ContainerInterface $container, private I_TableauService $tableauService)
    {
        parent::__construct($container);
    }

    #[Route('/api/tableau/{id}/modify-name', name: 'app_tableau_api_modify_name', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modifyName(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->tableauService->modifyName($data['titretableau'], $login, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/tableau/{id}/add-user', name: 'app_tableau_api_add_user', requirements: ['id' => Requirement::DIGITS], methods: ['POST'])]
    public function addUser(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->tableauService->addUser($data['userslogin'], $login, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/tableau/{id}/modify-role', name: 'app_tableau_api_modify_role', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modifyRole(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->tableauService->modifyRole($data['userslogin'], $data['userrole'], $login, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/tableau/{id}/delete-user', name: 'app_tableau_api_delete_user', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function deleteUser(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->tableauService->deleteUser($data['userslogin'], $login, $id);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json(null, 204);
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

    #[Route('/api/tableau/join/{codetableau}', name: 'app_tableau_api_join', methods: ['GET'])]
    public function join(Request $request, string $codetableau): Response
    {
        $result = $this->tableauService->joinTableau($this->getLoginFromJwt($request), $codetableau);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }
}

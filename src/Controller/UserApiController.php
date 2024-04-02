<?php

namespace App\Controller;

use App\Lib\Security\UserConnection\MotDePasse;
use App\Service\I_UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserApiController extends GeneriqueController
{

    public function __construct(ContainerInterface $container, private I_UserService $userService)
    {
        parent::__construct($container);
    }

    #[Route('/api/user/modify-name', name: 'app_user_api_modify_name', methods: ['PATCH'])]
    public function modifyName(Request $request):Response
    {
        $login = $this->getLoginFromJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->userService->modifyName($data['nom'], $login);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }
}

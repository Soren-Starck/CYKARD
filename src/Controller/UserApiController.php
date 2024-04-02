<?php

namespace App\Controller;

use App\Service\I_UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $login = $this->getLoginFromCookieJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->userService->modifyName($data['nom'], $login);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/user/modify-prenom', name: 'app_user_api_modify_prenom', methods: ['PATCH'])]
    public function modifyPrenom(Request $request):Response
    {
        $login = $this->getLoginFromCookieJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->userService->modifyPrenom($data['prenom'], $login);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/user/modify-mail', name: 'app_user_api_modify_mail', methods: ['PATCH'])]
    public function modifyMail(Request $request):Response
    {
        $login = $this->getLoginFromCookieJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->userService->modifyMail($data['email'], $login);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }

    #[Route('/api/user/modify-password', name: 'app_user_api_modify_password', methods: ['PATCH'])]
    public function modifyPassword(Request $request):Response
    {
        $login = $this->getLoginFromCookieJwt($request);
        $data = json_decode($request->getContent(), true);
        $result = $this->userService->modifyPassword($data['old_password'],$data['new_password'], $login);
        if (isset($result['error'])) return $this->json(['error' => $result['error']], $result['status']);
        return $this->json($result, 200);
    }
}

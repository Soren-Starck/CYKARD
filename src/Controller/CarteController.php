<?php

namespace App\Controller;

use App\Repository\CarteRepository;
use App\Repository\ColonneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class CarteController extends GeneriqueController
{
    private CarteRepository $carteRepository;
    private ColonneRepository $colonneRepository;

    public function __construct(CarteRepository $carteRepository, ColonneRepository $colonneRepository)
    {
        $this->carteRepository = $carteRepository;
        $this->colonneRepository = $colonneRepository;
    }

    #[Route('/api/carte/{id}/modify', name: 'app_carte_api_modify', requirements: ['id' => Requirement::DIGITS], methods: ['PATCH'])]
    public function modify(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$this->carteRepository->verifyUserTableauByCardAndAccess($id, $login)) return $this->json(['error' => 'Access denied'], 403);
        $data = json_decode($request->getContent(), true);
        if (array_key_exists('titrecarte', $data) && !$data['titrecarte']) return $this->json(['error' => 'Titre de carte manquant'], 400);
        $dbResponse = $this->carteRepository->updateCardWithAssign($id, $data, $login);
        if (!$dbResponse) return $this->json(['error' => 'Error updating the card'], 500);
        return $this->show($request, $id);
    }

    #[Route('/api/carte/{id}', name: 'app_carte_api_show', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(Request $request, int $id): Response
    {
        if (!$this->carteRepository->verifyUserTableauByCard($id, $this->getLoginFromJwt($request))) return $this->json(['error' => 'Access denied'], 403);
        $dbResponse = $this->carteRepository->find($id);
        if (!$dbResponse) return $this->json(['error' => 'No carte found'], 404);
        return $this->json($dbResponse[0], 200);
    }

    #[Route('/api/carte/{id}/delete', name: 'app_carte_api_delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $login = $this->getLoginFromJwt($request);
        if (!$this->carteRepository->verifyUserTableauByCardAndAccess($id, $login)) return $this->json(['error' => 'Access denied'], 403);
        $dbResponse = $this->carteRepository->delete($id);
        if (!$dbResponse) return $this->json(['error' => 'Erreur lors de la suppression de la carte'], 500);
        return $this->json(null, 204);
    }

    #[Route('/api/colonne/{colonne_id}/carte', name: 'app_carte_api_create', requirements: ['colonne_id' => Requirement::DIGITS], methods: ['POST'])]
    public function create(Request $request, int $colonne_id): Response
    {
        $data = json_decode($request->getContent(), true);
        $titre = array_key_exists('titrecarte', $data) ? $data['titrecarte'] : null;
        if (!$titre) return $this->json(['error' => 'Titre de carte manquant'], 400);
        if (!$this->colonneRepository->verifyUserTableauByColonne($this->getLoginFromJwt($request), $colonne_id)) return $this->json(['error' => 'Access denied'], 403);
        $descriptifcarte = array_key_exists('descriptifcarte', $data) ? $data['descriptifcarte'] : null;
        $couleurcarte = array_key_exists('couleurcarte', $data) ? $data['couleurcarte'] : null;
        $dbResponse = $this->carteRepository->create($data['titrecarte'], $descriptifcarte, $couleurcarte, $colonne_id);
        if (!$dbResponse) return $this->json(['error' => 'Erreur lors de la création de la carte'], 500);
        return $this->json($dbResponse->toArray(), 201);
    }

}
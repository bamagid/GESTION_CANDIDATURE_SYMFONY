<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Entity\Formation;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[Route('/api', name: 'api_')]
class FormationController extends AbstractController
{
    protected $container;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }
    public function acceptCandidature(Candidature $candidature): JsonResponse
    {
        $entityManager = $this->entityManager;
        $Statut = $entityManager->getRepository(Statut::class)->find(2);
        $candidature->setStatut($Statut);

        $entityManager->flush();

        return $this->json(
            [
                'message' => 'Candidature acceptée avec succès.',
                'candidatrure' => $candidature
            ],
            200
        );
    }
    public function refuseCandidature(Candidature $id): JsonResponse
    {
        $entityManager = $this->entityManager;
        $entityManager = $this->entityManager;
        $Statut = $entityManager->getRepository(Statut::class)->find(3);
        $id->setStatut($Statut);

        $entityManager->flush();

        return $this->json(
            [
                'message' => 'Candidature refusée avec succès.',
                'candidatrure' => $id
            ],
            200
        );
    }
    public function ListAcceptedCandidatures(): JsonResponse
    {
        $entityManager = $this->entityManager;
        $acceptedCandidatures = $entityManager->getRepository(Candidature::class)->findBy(['statut' => 2]);

        return $this->json(
            [
                'accepted_candidatures' => $acceptedCandidatures,
                'total' => count($acceptedCandidatures)
            ]
        );
    }
    public function ListRefusedCandidatures(): JsonResponse
    {
        $entityManager = $this->entityManager;
        $acceptedCandidatures = $entityManager->getRepository(Candidature::class)->findBy(['statut' => 3]);
        return $this->json(
            [
                'accepted_candidatures' => $acceptedCandidatures,
                'total' => count($acceptedCandidatures)
            ]
        );
    }
    public function FormationAcceptedCandidatures(Formation $id): JsonResponse
    {
        $entityManager = $this->entityManager;
        $acceptedCandidatures = $entityManager->getRepository(Candidature::class)->findBy(['formation' => $id, 'statut' => 2]);

        return $this->json(
            [
                'accepted_candidatures' => $acceptedCandidatures,
                'total' => count($acceptedCandidatures)
            ]
        );
    }

    public function FormationRefusedCandidatures(Formation $id): JsonResponse
    {
        $entityManager = $this->entityManager;
        $refusedCandidatures = $entityManager->getRepository(Candidature::class)->findBy(['formation' => $id, 'statut' => 3]);

        return $this->json(
            [
                'refused_candidatures' => $refusedCandidatures,
                'total' => count($refusedCandidatures)
            ]
        );
    }
    public function closeFormation(Formation $id): JsonResponse
    {
        $id->setIsFenced(true);

        $entityManager = $this->entityManager;
        $entityManager->flush();

        return $this->json([
            'message' => 'Formation clôturée avec succès.',
            'formation' => $id
        ]);
    }
}

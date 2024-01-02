<?php

namespace App\EventSubscriber;

use App\Entity\Role;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class RegistrationSubscriber implements EventSubscriberInterface
{
    private $passwordHasher;
    private $entityManager;
    private $jWTManagery;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, JWTTokenManagerInterface $jWTManagery)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
        $this->jWTManagery = $jWTManagery;
    }
    public function onKernelREQUEST(RequestEvent $event): void
    {
        $entityManager = $this->entityManager;
        $JWT = $this->jWTManagery;
        $events = $event->getRequest();
        if ($events->isMethod("POST") &&  $events->getPathInfo() === "/api/users") {
            // Récupérer les données JSON
            $data = json_decode($events->getContent(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $response = new JsonResponse(['error' => 'Les données JSON sont mal formées.'], 400);
                $event->setResponse($response);
                $event->stopPropagation();
                return;
            }
            // Vérifier la présence des données requises
            $requiredFields = ['nom', 'prenom', 'adresse', 'telephone', 'email', 'password'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    $response = new JsonResponse(['error' => sprintf('Le champ %s est requis.', $field)], 400);
                    $event->setResponse($response);
                    return;
                }
            }
            // Rechercher un rôle par son ID (supposons que l'ID du rôle que vous recherchez est 1)
            $role = $entityManager->getRepository(Role::class)->find(2);

            // Vérifier si le role existe
            if (!$role) {
                $response = new JsonResponse(['error' => 'Role non trouvé.'], 404);
                $event->setResponse($response);
                $event->stopPropagation();
                return;
            } else {
                // Création de l'utilisateur et enregistrement dans le manager
                $user = new User();
                $user->setNom($data["nom"]);
                $user->setPrenom($data["prenom"]);
                $user->setAdresse($data['adresse']);
                $user->setTelephone($data['telephone']);
                $user->setEmail($data['email']);
                $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
                $user->setRoles($role);
                $user->setCreatedAt(new DateTimeImmutable());
                // Enregistrer l'utilisateur dans la base de données
                $entityManager->persist($user);
                $entityManager->flush();
                $token = $JWT->create($user);
                // Renvoyer une réponse
                $response = new JsonResponse([
                    'success' => 'Utilisateur créé avec succès.',
                    'token' => $token
                ], 201);
                $event->setResponse($response);
                //Arrêter la propagation de l'événement  
            }
            $event->stopPropagation();
        }


        // Faire quelque chose avec les données
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelREQUEST',
        ];
    }
}

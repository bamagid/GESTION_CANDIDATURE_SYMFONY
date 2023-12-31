<?php

namespace App\EventSubscriber;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUserSubscriber implements EventSubscriberInterface
{
    private $passwordHasher;
    private $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function onKernelREQUEST(RequestEvent $event): void
    {
        $entityManager = $this->entityManager;
        $request = $event->getRequest();

        // Assurez-vous que la requête est une requête PATCH ou PUT pour la modification
        if ($request->isMethod("PATCH") && strpos($request->getPathInfo(), "/api/users/") === 0) {
            $pathInfo = $request->getPathInfo();
            $id = basename($pathInfo);
            // Récupérer l'utilisateur à partir de l'ID
            $user = $entityManager->getRepository(User::class)->loadUserById($id);
            $response = new  JsonResponse(["user" => $user]);
            $event->setResponse($response);
            $event->stopPropagation();
            return;
            // Vérifier si le role exis

            // Vérifier si l'utilisateur existe
            if (!$user) {
                $response = new JsonResponse(['error' => 'Utilisateur non trouvé.'], 404);
                $event->setResponse($response);
                $event->stopPropagation();
                return;
            }

            // Récupérer les données JSON
            $data = json_decode($request->getContent(), true);
            $user->setNom($data["nom"]);
            $user->setPrenom($data["prenom"]);
            $user->setAdresse($data['adresse']);
            $user->setTelephone($data['telephone']);
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
            // Enregistrez les modifications dans la base de données
            $entityManager->flush();

            // Renvoyer une réponse
            $response = new JsonResponse(['success' => 'Utilisateur modifié avec succès.', 'user' => $user], 200);
            $event->setResponse($response);
            $event->stopPropagation();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelREQUEST',
        ];
    }
}

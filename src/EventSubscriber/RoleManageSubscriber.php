<?php

namespace App\EventSubscriber;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RoleManageSubscriber implements EventSubscriberInterface
{
    private $securite;
    public function __construct(Security $security)
    {
        $this->securite = $security;
    }
    public function onKernelRequest(RequestEvent $event): void
    {
        $role = [];
        if ($this->securite->getUser()) {
            $role = $this->securite->getUser()->getRoles();
        }
        $events = $event->getRequest();
        if ($events->isMethod("POST") &&  $events->getPathInfo() === "/api/candidatures" && $role !== ["Candidat"]) {
            // Récupérer les données JSON
            $response = new JsonResponse(['error' => "Cette route n'est accessible qu'aux candidats"], 403);
            $event->setResponse($response);
            $event->stopPropagation();
            return;
        } else if (($events->isMethod("GET") && ((strpos($events->getPathInfo(), "/api/formations/") === 0) ||  ($events->getPathInfo() === "/api/formations" || $events->getPathInfo() === "/api")) || (($events->isMethod("POST") && ($events->getPathInfo() === "/api/login"))))) {
        } else {
            $response = new JsonResponse(['error' => "Cette route n'est accessible qu'aux admins"], 403);
            $event->setResponse($response);
            $event->stopPropagation();
            return;
        }
    }


    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}

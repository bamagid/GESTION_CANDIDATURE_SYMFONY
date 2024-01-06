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

        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();
        $method = $request->getMethod();

        if ($method === "POST" && $pathInfo === "/api/candidatures" && $role !== ["Candidat"]) {
            // Seuls les candidats peuvent accéder à la route POST /api/candidatures
            $response = new JsonResponse(['error' => "Cette route n'est accessible qu'aux candidats"], 403);
            $event->setResponse($response);
            $event->stopPropagation();
            return;
        }
        // } elseif (
        //     (
        //         ($method === "GET" && (
        //             strpos($pathInfo, "/api/formations/") === 0 ||
        //             $pathInfo === "/api/formations" ||
        //             $pathInfo === "/api"
        //         )) ||
        //         ($method === "POST" && (
        //             $pathInfo === "/api/login" ||
        //             $pathInfo === "/api/users"
        //         ))
        //     )
        // ) {
        //     echo "";
        // } else {
        //     $response = new JsonResponse(['error' => "Cette route n'est accessible qu'aux admins"], 403);
        //     $event->setResponse($response);
        //     $event->stopPropagation();
        //     return;
        // }
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}

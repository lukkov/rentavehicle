<?php

declare(strict_types=1);

namespace App\Listener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DebugFollowAjaxListener implements EventSubscriberInterface
{
    private bool $isDebug;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->isDebug = $parameterBag->get('kernel.debug');
    }

    public function onResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set('Symfony-Debug-Toolbar-Replace', 1);
    }

    public static function getSubscribedEvents()
    {
        return [
           KernelEvents::RESPONSE => ['onResponse']
        ];
    }
}

<?php

namespace App\EventDispatcher;

use App\Event\MessageSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessageSuccessEmailSubscriber implements EventSubscriberInterface
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'message.success' => 'sendSuccessEmail'
        ];
    }
    public function sendSuccessEmail(MessageSuccessEvent $messageEvent)
    {
        $this->logger->info("Email envoyé à " . $messageEvent->getMessage()->getEmail());
    }
}

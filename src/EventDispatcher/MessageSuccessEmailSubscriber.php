<?php

namespace App\EventDispatcher;

use App\Event\MessageSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MessageSuccessEmailSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            'message.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(MessageSuccessEvent $messageEvent)
    {
        $email = new Email();
        $fromEmail = $messageEvent->getMessage()->getEmail();
        $nom = $messageEvent->getMessage()->getNom();
        $prenom = $messageEvent->getMessage()->getPrenom();
        $email->from(new Address($fromEmail, $nom . ' ' . $prenom))
            ->to("admin@test.com")
            ->text("Le service admin a bien reçu votre message")
            ->subject("Reception du message");

        $this->mailer->send($email);

        $this->logger->info("Email envoyé à " . $fromEmail);
        //$this->mailer->send()
    }
}

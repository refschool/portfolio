<?php

namespace App\EventDispatcher;

use App\Event\ContactSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ContactSuccessEmailSubscriber implements EventSubscriberInterface
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

    public function sendSuccessEmail(ContactSuccessEvent $messageContactEvent)
    {
        $email = new TemplatedEmail();
        $fromEmail = $messageContactEvent->getContact()->getEmail();
        $nom = $messageContactEvent->getContact()->getNom();
        $prenom = $messageContactEvent->getContact()->getPrenom();
        $email
            ->from(new Address($fromEmail, $nom . ' ' . $prenom))
            ->to("admin@test.com")
            ->text("Le service admin a bien reçu votre message")
            ->htmlTemplate('emails/contact_view.html.twig')
            ->context([
                'contact' => $messageContactEvent->getContact()
            ])
            ->subject("Reception du message");

        $this->mailer->send($email);

        $this->logger->info("Email envoyé à " . $fromEmail);
        //$this->mailer->send()
    }
}

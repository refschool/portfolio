<?php

namespace App\EventDispatcher;

use App\Event\InscriptionSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class InscriptionSuccessEmailSubscriber implements EventSubscriberInterface
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
            'user.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(InscriptionSuccessEvent $userEvent)
    {
        $email = new TemplatedEmail();
        $fromEmail = $userEvent->getUser()->getEmail();
        $fullname = $userEvent->getUser()->getFullname();
        $email
            ->from(new Address($fromEmail, $fullname))
            ->to("screfield@gmail.com")
            ->text("Bienvenue. Inscription réussi.")
            ->htmlTemplate('emails/inscription_view.html.twig')
            ->context([
                'user' => $userEvent->getUser()
            ])
            ->subject("Inscription");

        $this->mailer->send($email);

        $this->logger->info("Email envoyé à " . $fromEmail);
        //$this->mailer->send()
    }
}

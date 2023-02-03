<?php

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Entity\User;
use App\Event\MessageSuccessEvent;
use App\Form\ContactType;
use App\Form\ForgottenPasswordType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="envoyerMessageContact")
     */
    public function envoyerMessageContact(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $vi,
        FlashBagInterface $flashBag,
        EventDispatcherInterface $dispatcher
    ) {
        $flashBag->add('info', 'Le formulaire est en cours de développement.');


        //getForm + setData
        $form = $this->createForm(ContactType::class);
        //analyse request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact = $form->getData();

            //$em->persist($contact);
            //$em->flush();


            // Lancer un évènement qui permettent aux autres développeurs de réagir à la soumission d'un message
            $contactEvent = new MessageSuccessEvent($contact);

            $dispatcher->dispatch($contactEvent, 'message.success');

            $flashBag->add('success', 'Votre message a été envoyé.');
            $flashBag->add('success', 'Vous recevrez une copie de votre message.');
        }

        $formView = $form->createView();

        return $this->render('contact/contact.html.twig', [
            'formView' => $formView
        ]);
    }

    public function confirm()
    {
    }

    /**
     * @Route("/admin/editerMessageContact/{id}", name="messageContactEdit")
     */
    public function editerMessageContact($id, ContactRepository $contactRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        $message = $contactRepository->find($id);


        $form = $this->createForm(ContactType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('contact/edit.html.twig', [
            'contact' => $message,
            'formView' => $formView
        ]);
    }

    /**
     *  @Route("/forgottenPassword", name="forgottenPassword")
     */
    public function forgottenPassword(UserRepository $userRepository, Request $request, EntityManagerInterface $em, MailerInterface $mailer)
    {

        $form = $this->createForm(EmailForgottenPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $email = $form->getData();

            //verifie email

            $user = $em->getRepository(User::class)
                ->findByEmail($email);

            $email = new TemplatedEmail();
            $fromEmail = 'reset-no-reply@gmail.com';
            $email
                ->from(new Address($fromEmail, 'Bot Reset'))
                ->to("admin@test.com")
                ->text("Le service admin a bien reçu votre message")

                ->subject("Reset MDP");

            $mailer->send($email);

            //$this->mailer->send()  
            //$em->flush();
            dd($user);

            //dd($email);
            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('forgottenPassword.html.twig', [

            'formView' => $formView
        ]);
    }
}

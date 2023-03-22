<?php

namespace App\Controller\Contact;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Event\ContactSuccessEvent;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ContactController extends AbstractController
{
    protected $recaptcha3Validator;
    protected $em;
    protected $flashBag;
    protected $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher, FlashBagInterface $flashBag, Recaptcha3Validator $recaptcha3Validator, EntityManagerInterface $em)
    {
        $this->recaptcha3Validator = $recaptcha3Validator;
        $this->em = $em;
        $this->flashBag = $flashBag;
        $this->dispatcher = $dispatcher;
    }
    /**
     * @Route("/contact", name="envoyerMessageContact")
     */
    public function envoyerMessageContact(
        Request $request,
        SluggerInterface $slugger
    ) {

        $contact = new Contact;
        //getForm + setData
        $form = $this->createForm(ContactType::class);
        //analyse request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupère le score
            $score = $this->recaptcha3Validator->getLastResponse()->getScore();

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();
            $contact = $form->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();
                $contact->setBrochureFilename($newFilename);

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents

            }

            $this->em->persist($contact);
            $this->em->flush();


            // Lancer un évènement qui permettent aux autres développeurs de réagir à la soumission d'un message
            $contactEvent = new ContactSuccessEvent($contact);

            $this->dispatcher->dispatch($contactEvent, 'message.success');

            $this->flashBag->add('success', 'Votre message a été envoyé.');
            //$flashBag->add('success', 'Vous recevrez une copie de votre message.');
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
    public function editerMessageContact(
        $id,
        ContactRepository $contactRepository,
        Request $request,
        EntityManagerInterface $em
    ) {

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
     * @Route("/inscription", name="inscription")
     */
    public function inscription(
        Request $request,
        UserPasswordHasherInterface $encoder
    ) {
        //$flashBag->add('info', 'Le formulaire est en cours de développement.');

        $user = new User;
        //getForm + setData
        $form = $this->createForm(UserType::class);
        //analyse request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupère le score
            $score = $this->recaptcha3Validator->getLastResponse()->getScore();

            $user = $form->getData();
            $password = $user->getPassword();

            $hash = $encoder->hashPassword($user, $password);
            $user->setPassword($hash);

            $this->em->persist($user);
            $this->em->flush();
        }

        $formView = $form->createView();

        return $this->render('contact/inscription.html.twig', [
            'formView' => $formView
        ]);
    }
}

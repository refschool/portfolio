<?php

namespace App\Controller;

use Doctrine\ORM\Entity;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{

    /**
     * @Route("/contact", name="envoyerMessageContact")
     */
    public function envoyerMessageContact(Request $request, EntityManagerInterface $em, ValidatorInterface $vi)
    {

        //getForm + setData
        $form = $this->createForm(ContactType::class);
        //analyse request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contact = $form->getData();

            $em->persist($contact);
            $em->flush();
            dd($contact);
        }

        $formView = $form->createView();

        return $this->render('contact/contact.html.twig', [
            'formView' => $formView
        ]);
    }


    /**
     * @Route("/admin/{id}/editerMessageContact", name="messageContactEdit")
     */
    public function editerMessageContact($id, ContactRepository $contactRepository, Request $request,
    EntityManagerInterface $em){
        $message = $contactRepository->find($id);

        $form = $this->createForm(ContactType::class, $message);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('contact/edit.html.twig',[
            'contact' => $message,
            'formView' => $formView
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\Entity;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
    EntityManagerInterface $em, ValidatorInterface $validator){
        
        $client = [
            'nom' => '',
            'prenom' => 'John',
            'voiture' => [
                'marque' => '',
                'couleur' => 'Noire'
            ]
        ];

        $collection = new Collection([
            'nom' => new NotBlank(['message' => "Le nom ne doit pas être vide"]),
            'prenom' => [
                new NotBlank(['message' => "Le prenom ne doit pas être vide"]),
                new Length(['min' => 3, 'minMessage' => "Le prenom ne doit pas faire moins de 3 caractères"])
            ],
            'voiture' => new Collection([
                'marque' => new NotBlank(['message'=> 'La marque ne doit pas être vide']),
                'couleur' => new NotBlank(['message'=> 'La couleur ne doit pas être vide']),
            ])
        ]);
        
        $erreurs  = $validator->validate($client, $collection);
        //Compte le nombre d'erreurs
        if ($erreurs->count() > 0 ){
            dd("Il y a des erreurs", $erreurs);
        }
        dd("Tout va bien");
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

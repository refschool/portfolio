<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre nom'],
                'required' => false,
                'constraints' => new Assert\NotBlank([
                    'message' => 'Validation du formulaire : Le nom ne peut pas être vide.'
                ])
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre prenom'],
                'required' => false
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre telephone'],
                'required' => false
            ])
            ->add('email', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre email'],
                'required' => false
            ])
            ->add('message', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre message'],
                'required' => false
            ]);

        // Evenement : affiche le bloc nom si l'id est null
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            /** @var Contact */
            /*   
                $contact = $event->getData();
         
                if($contact->getId() === null) {
                    $form->add('nom', TextType::class, [
                        'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre nom'],
                        'required' => false,
                    ]);
                } */
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

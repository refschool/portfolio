<?php

namespace App\Form;

use App\Entity\User;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre email'],
                'required' => true
            ])
            ->add('password', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre mot de passe'],
                'required' => true
            ])
            ->add('fullname', TextType::class, [
                'attr' => ['class' => 'form-group form-control col-md-12', 'placeholder' => 'Tapez votre nom prenom'],
                'required' => false
            ])

            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'homepage',
                'locale' => 'fr',
            ]);;

        // Evenement : affiche le bloc nom si l'id est null
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();

            /** @var User */
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
            'data_class' => User::class,
        ]);
    }
}

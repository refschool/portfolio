<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordForgottenPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-group form-control col-md-12',
                    'placeholder' => 'Tapez votre email'
                ],
                'required' => false
            ])
            ->add('Nouveau mot de passe', PasswordType::class, [
                'attr' => [
                    'class' => 'form-group form-control col-md-12',
                    'placeholder' => 'Nouveau mot de passe'
                ],
                'required' => false
            ])
            ->add('Confirmation mot de passe', PasswordType::class, [
                'attr' => [
                    'class' => 'form-group form-control col-md-12',
                    'placeholder' => 'Confirmer le nouveau mot de passe'
                ],
                'required' => false
            ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez votre nom'],
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez votre prenom'],
                'required' => false
            ])->add('telephone', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez votre telephone'],
                'required' => false
            ])->add('email', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez votre email'],
                'required' => false
            ])->add('message', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez votre message'],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

<?php

namespace App\Controller\Contact;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class ContactConfirmationController
{
    protected $formFactory;
    protected $router;
    protected $security;

    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, Security $security)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->security = $security;
    }

    /**
     * @Route("contact/confirm", name="contact_confirm")
     */
    public function confirm(Request $request, FlashBagInterface $flashbag)
    {
        // 1. Lire les données du formulaire : FormFactoryInterface / Request
        $form = $this->formFactory->create();

        // 2. Si le formulaire n'a pas été soumis : dégager
        if (!$form->isSubmitted()) {
            // Message Flash puis Redirection (FlahsBagInterface)
            $flashbag->add('warning', 'Vous devez remplir le formulaire de confirmation');
            return new RedirectResponse($this->router->generate('contact_confirm'));
        }

        // 3. Si je ne suis pas connecté : dégager (Security)
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException("vous devez être connecté pour confirmer.");
        }

        // 4. Nous allons crée un Contact

        // 5. Nous allons enregistrer le contact (EntityManagerInterface)
    }
}

<?php

namespace App\Controller;

use App\Form\EmailForgottenPasswordType;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {

        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);

        return $this->render('security/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }

    /**
     *  @Route("/emailForgottenPassword", name="emailForgottenPassword")
     */
    public function emailForgottenPassword(UserRepository $userRepository, Request $request, EntityManagerInterface $em, MailerInterface $mailer)
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

        return $this->render('emailForgottenPassword.html.twig', [

            'formView' => $formView
        ]);
    }
}

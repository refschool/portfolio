<?php

namespace  App\Controller\Purchase;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class PurchaseListController extends AbstractController
{
    protected $security;
    protected $router;
    protected $twig;

    public function __construct(Security $security, RouterInterface $router, Environment $twig)
    {
        $this->security = $security;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @Route("/purchases", name="purchase_index")
     */
    public function index()
    {
        // 1. S'assurer que la personne soit connecté.


        /** @var User */
        $user = $this->security->getUser();
        if (!$user) {
            //throw new AccessDeniedException("Vous devez être connecté pour consulter vos commandes.");
            // Redirection -> RedirectResponse
            //Générer une URL en fonction du nom d'une route -> URLGeneratorInterface / RouterInterface
            $url =  $this->router->generate('security_login');
            return new RedirectResponse($url);
        }

        // 2. Savoir qui est connecté
        // 3. Passer l'utilisateur connecté vers Twig
        $html = $this->twig->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
        return new Response($html);
    }
}

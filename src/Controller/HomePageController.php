<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomePageController extends AbstractController
{
    protected $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * @Route("/", name="homepage")
     */
    public function hello(EntityManagerInterface $em, $prenom, Request $request)
    {

        return $this->render('hello.html.twig');
    }

    /**
     * @Route("/cv", name="cv")
     */
    public function cv()
    {
        return $this->render('cv.html.twig');
    }

    /**
     * @Route("/projets", name="projets")
     */
    public function projets()
    {
        return $this->render('projets.html.twig');
    }

    /**
     * @Route("/don", name="don")
     */
    public function don()
    {
        return $this->render('don.html.twig');
    }

    /**
     * @Route("/dontpn", name="dontpn")
     */
    public function dontpn()
    {
        return $this->render('dontpn.html.twig');
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function upload()
    {
        return $this->render('upload.html.twig');
    }
}

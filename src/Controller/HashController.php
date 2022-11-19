<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashController extends AbstractController
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @Route("/test", name="test")
     */
    public function ashe(EntityManagerInterface $em)
    {
        /*
        //Creer Admin
        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "admin");
        $admin->setEmail("admin@gmail.com")
            ->setPassword($hash)
            ->setFullname("Admin")
            ->setRoles(['ROLE_ADMIN']);
        $em->persist($admin);

        //Creer Users
        for ($u = 5; $u < 10; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail("user$u@gmail.com")
                ->setFullname("00 $u")
                ->setPassword($hash);
            $em->persist($user);
        }
        $em->flush();
        return $this->render('hello.html.twig');*/
    }
}

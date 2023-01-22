<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');

        $admin = new User;
        $hash = $this->encoder->encodePassword($admin, "admin");
        $admin->setEmail("admin@gmail.com")
            ->setFullname("admin")
            ->setPassword($hash)
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");
            $user->setEmail("user$u@gmail.com")
                ->setFullname($faker->name())
                ->setPassword($hash);
            $manager->persist($user);
        }
        $manager->flush();
    }
}

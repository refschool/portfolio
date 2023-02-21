<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $encoder;
    protected $slugger;

    public function __construct(UserPasswordEncoderInterface $encoder, SluggerInterface $slugger)
    {
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        for ($p = 0; $p < 100; $p++) {
            $product = new Product;
            $product->setName($faker->productName)
                ->setPrice($faker->price(5000, 20000))
                ->setSlug(strtolower($this->slugger->slug($product->getName())));
            $manager->persist($product);
        }
        $manager->flush();
        /*
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
*/
        $manager->flush();
    }
}

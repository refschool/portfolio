<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $encoder;
    protected $slugger;

    public function __construct(UserPasswordHasherInterface $encoder, SluggerInterface $slugger)
    {
        $this->encoder = $encoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        for ($c = 0; $c < 3; $c++) {
            $category = new Category;
            $category->setName($faker->department)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;
                $product->setName($faker->productName)
                    ->setPrice($faker->price(5000, 20000))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true));

                $manager->persist($product);
            }
        }

        $admin = new User;
        $hash = $this->encoder->hashPassword($admin, "admin");

        $admin->setEmail("admin@gmail.com")
            ->setFullname("admin")
            ->setPassword($hash)
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        $users = [];
        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setEmail("user$u@gmail.com")
                ->setFullname($faker->name())
                ->setPassword($hash);
            $manager->persist($user);
            $users[] = $user;
        }

        for ($p = 0; $p < mt_rand(20, 40); $p++) {
            $purchase = new Purchase;
            $purchase->setFullName($faker->name())
                ->setAddress($faker->address())
                ->setPostalCode($faker->postcode())
                ->setCity($faker->city())
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->dateTimeBetween('-6 months'));
            if ($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }
            $manager->persist($purchase);
        }


        $manager->flush();
    }
}

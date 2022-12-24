<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
    
        $faker = Factory::create('fr_FR');
        for($c = 0 ; $c < 1 ; $c++){
            $contact = new Contact;
            $contact->setNom($faker->name());
            $contact->setPrenom($faker->name());
            $contact->setEmail($faker->email());
            $contact->setTelephone($faker->randomDigit());
            $contact->setMessage($faker->text());

            $manager->persist($contact);
        }
        $manager->flush();
    }
}

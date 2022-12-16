<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
    
        for($c = 0 ; $c < 3 ; $c++){
            $contact = new Contact;
            $contact->setNom("Nom n°$c");
            $contact->setPrenom("Prenom $c");
            $contact->setEmail("test$c@text$c.com");
            $contact->setTelephone("0123456789");
            $contact->setMessage("Message");

            $manager->persist($contact);
        }
        $manager->flush();
    }
}

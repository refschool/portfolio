<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Class Contact Validation du formulaire : le nom est obligatoire.")
     * @Assert\Length(min=2, minMessage="MINIMUM 2 Caractères  ")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'email est obligatoire")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le téléphone est obligatoire")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez mettre un message")
     */
    private $message;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        /*         $metadata->addPropertyConstraints(
            'nom',
            [
                new Assert\NotBlank(['message' => 'Class Contact Le nom est obligatoire']),
                new Assert\Length([
                    'min' => 3,
                    'max' => 4,
                    'minMessage' => 'Le nom doit contenir au moins 3 caractères',
                    'maxMessage' => 'Le nom doit contenir 4 caractères max'
                ])
            ]
        );
        $metadata->addPropertyConstraint(
            'prenom',
            new Assert\NotBlank(['message' => 'Le prénom est obligatoire'])
        ); */
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }
    // "?" permet de passer valeur vide formulaire
    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }
}

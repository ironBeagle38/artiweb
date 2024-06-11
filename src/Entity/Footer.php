<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Controller\FooterController;
use App\Repository\FooterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: FooterRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/footer',
            controller: FooterController::class,
            normalizationContext: ['groups' => ['read:Footer']],
            denormalizationContext: ['groups' => ['write:Footer']],
            deserialize: false,
        ),
        new Get(
            uriTemplate: '/footer/1',
            normalizationContext: ['groups' => ['read:Footer']],
            denormalizationContext: ['groups' => ['write:Footer']],
        ),
        new Post(
            uriTemplate: '/footer/{id}/updateFooter',
            controller: FooterController::class,
            normalizationContext: ['groups' => ['read:Footer']],
            denormalizationContext: ['groups' => ['write:Footer']],
            deserialize: false,
            name: 'updateFooter',
        )
    ]
)]
#[Vich\Uploadable]
class Footer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:Footer'])]
    private ?int $id = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;


    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $city = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postalCode = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facebook = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $twitter = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instagram = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkedin = null;

    #[Groups(['read:Footer', 'write:Footer'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagePath = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Vich\UploadableField(mapping: 'footer_image', fileNameProperty: 'imagePath')]
    private ?File $file = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): static
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): static
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): static
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): static
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(?string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): static
    {
        $this->file = $file;
        if (null !== $file) {
            $this->setUpdatedAt(new \DateTimeImmutable());
        }

        return $this;
    }

    #[Groups(['read:Footer'])]
    public function getImageUrl(): ?string
    {
        if ($this->getImagePath()) {
            return '/images/footer/' . $this->getImagePath();
        }

        return null;
    }
}

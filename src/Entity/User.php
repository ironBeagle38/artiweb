<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\UserChangePasswordController;
use App\Controller\UserInsertUserController;
use App\Controller\UserUpdateUserController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ApiResource(
    operations: [
        new Put(
            uriTemplate: '/users/{id}/changePassword',
            controller: UserChangePasswordController::class,
            denormalizationContext: ['groups' => ['put:User:changePassword']],
            security: "is_granted('ROLE_USER') and object.getId() == user.getId()",
            name: 'changePassword'
        ),
        new Post(
            uriTemplate: '/users/{id}/updateUser',
            controller: UserUpdateUserController::class,
            denormalizationContext: ['groups' => ['post:User:updateUser']],
            security: "is_granted('ROLE_USER') and object.getId() == user.getId()",
            deserialize: false,
            name: 'updateUser',
        ),
        new Post(
            uriTemplate: '/users',
            controller: UserInsertUserController::class,
            denormalizationContext: ['groups' => ['post:User:insertUser']],
            security: "is_granted('ROLE_ADMIN')",
            name: 'insertUser'
        )
    ],
    normalizationContext: ['groups' => ['read:User']]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    #[Assert\Email]
    #[Groups(['read:User', 'post:User:insertUser'])]
    private string $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['post:User:insertUser'])]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    #[Groups(['put:User:changePassword', 'post:User:insertUser'])]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:User', 'post:User:updateUser'])]
    private ?string $firstname = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:User', 'post:User:updateUser'])]
    private ?string $lastname = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:User', 'post:User:updateUser'])]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:User', 'post:User:updateUser'])]
    private ?string $address = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    #[Groups(['read:User', 'post:User:updateUser'])]
    private ?string $postCode = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:User', 'post:User:updateUser'])]
    private ?string $city = null;

    #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'filePath')]
    private ?File $file = null;

    #[Groups(['read:User', 'post:User:updateUser'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePath = null;

    #[Groups(['post:User:updateUser'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(['post:User:updateUser'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    // Getters et setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(?string $postCode): void
    {
        $this->postCode = $postCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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
}

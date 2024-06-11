<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserUpdateUserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(User $user, Request $request): Response
    {
        $data = $request->request->all();

        $file = $request->files->get('avatarFile');

        $fieldsToUpdate = [
            'firstName' => 'setFirstname',
            'lastName' => 'setLastname',
            'city' => 'setCity',
            'phone' => 'setPhoneNumber',
            'postCode' => 'setPostCode',
            'address' => 'setAddress'
        ];

        foreach ($fieldsToUpdate as $field => $method) {
            if (!empty($data[$field])) {
                $user->$method($data[$field]);
            }
        }

        if ($file) {
            $user->setFile($request->files->get('avatarFile'));
        }

        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
    }
}
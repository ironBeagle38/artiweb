<?php

namespace App\Controller;

use App\Entity\Footer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Footer $footer, Request $request): Response
    {
        $data = $request->request->all();

        $file = $request->files->get('file');

        $fieldsToUpdate = [
            'email' => 'setEmail',
            'phoneNumber' => 'setPhoneNumber',
            'address' => 'setAddress',
            'city' => 'setCity',
            'postalCode' => 'setPostalCode',
            'twitter' => 'setTwitter',
            'instagram' => 'setInstagram',
            'facebook' => 'setFacebook',
            'linkedin' => 'setLinkedin',
        ];

        foreach ($fieldsToUpdate as $field => $method) {
            $footer->$method($data[$field]);
        }

        if ($file) {
            $footer->setFile($request->files->get('file'));
        }
        $footer->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($footer);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'success', 'footer' => $footer], Response::HTTP_OK);
    }
}
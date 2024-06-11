<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?\Symfony\Component\HttpFoundation\Response
    {
        $user = $token->getUser();

        $userData = [
            'id' => $user->getId(),
            'roles' => $user->getRoles(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'postCode' => $user->getPostCode(),
            'city' => $user->getCity(),
            'address' => $user->getAddress(),
            'phone' => $user->getPhoneNumber(),
            'avatar' => $user->getFilePath() ? '/images/users/' . $user->getFilePath() : null
        ];

        $jwtToken = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $jwtToken, 'userData' => $userData]);
    }
}
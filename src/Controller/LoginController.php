<?php

declare(strict_types=1);

namespace App\Controller;

use App\Api\Resource\Login;
use App\Entity\User;
use App\Service\Token;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly Token $token,
        private readonly ValidatorInterface $validator,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $login = new Login();
        $login->email = $data['email'] ?? null;
        $login->password = $data['password'] ?? null;

        $violations = $this->validator->validate($login);
        if ($violations->count() > 0) {
            return $this->json($violations->get(0)->getMessage());
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $login->email]);
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $login->password)) {
            return $this->json([
                'result' => false,
                'error' => 'Le login ou le mot de passe est incorrect.'
            ]);
        }

        return $this->json([
            'result' => true,
            'user' => $user,
            'userId' => $user->id,
            'token' => $this->token->generateTokenForUser($user->email)
        ]);
    }
}

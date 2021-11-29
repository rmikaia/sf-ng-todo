<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'auth_registration')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $sentData = json_decode($request->getContent(), true);
        $user->setFirstname($sentData["firstname"]);
        $user->setLastname($sentData["lastname"]);
        $user->setEmail($sentData["email"]);
        $user->setLastname($sentData["address"] ?? "");
        $user->setBirth(new DateTime($sentData["birth"] ?? ""));
        $user->setPhone($sentData["phone"] ?? 0);
        $user->setPassword($passwordHasher->hashPassword(
            $user,
            $sentData["password"]
        ));

        $em->persist($user);
        $em->flush();

        return $this->json($user);
    }
}

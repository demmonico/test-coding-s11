<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/user", methods="GET", name="user_getall")
     * @return Response
     */
    public function getUsersAction(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->json([
            'users' => array_map(fn(User $user) => $user->asArray(), $users)
        ]);
    }

    /**
     * @Route("/user/{username}", methods="PUT", name="user_add")
     * @param string $username
     * @param Request $request
     * @return Response
     */
    public function addUserAction(string $username, Request $request): Response
    {
        try {
            $user = $this->userRepository->addUser($username, $request->request->get('phone'));
        } catch (UniqueConstraintViolationException $e) {
            return $this->json([
                'error' => $e->getMessage(),
                'errorCode' => $e->getCode(),
            ], 400);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
                'errorCode' => $e->getCode(),
            ], 500);
        }

        return $this->json([
            'id' => $user->getId(),
        ]);
    }
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/api/security")
 */
class SecurityController extends AbstractController
{

    /**
     * @var \App\Repository\UserRepository
     */
    private $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(Request $request): Response
    {
//        return $this->render('security/login.html.twig', [
//            // last username entered by the user (if any)
//            'last_username' => $helper->getLastUsername(),
//            // last authentication error (if any)
//            'error' => $helper->getLastAuthenticationError(),
//        ]);
        return new JsonResponse("error in login please try again");
    }

    /**
     * @Route("/newtoken", name="security_new_token")
     */
    public function getNewToken(): Response
    {
        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('authenticate')->getValue();
        return new JsonResponse($token);
    }

    /**
     * This is the route the user can use to logout.
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in config/packages/security.yaml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}

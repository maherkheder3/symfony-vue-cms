<?php

namespace App\Controller\Api\Admin;

use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/admin/")
 * @Security("has_role('ROLE_ADMIN')")
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class BackendController extends AbstractController
{
    /**
     * @var \App\Repository\CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/", methods={"GET"}, name="backend_index")
     */
    public function index(): Response
    {

    }

    /**
     * @Route("categores", methods={"GET"}, name="categories")
     */
    public function categores(): Response
    {
        $iterator = $this->categoryRepository->findAll();
        $latestPosts = [];

        foreach ($iterator as $item) {
            $latestPosts[] = $item->serializer();
        }

        return new JsonResponse($latestPosts);
    }
}

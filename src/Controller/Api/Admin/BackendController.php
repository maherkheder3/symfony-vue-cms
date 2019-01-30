<?php

namespace App\Controller\Api\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("categores", methods={"PUT"}, name="editCategories")
     * @param Request $request
     * @return JsonResponse
     */
    public function editCategores(Request $request): Response
    {
        try{
            $data = json_decode($request->getContent(), true);
            $category = $this->categoryRepository->find($data["category"]["id"]);
            $category->deserializer($data["category"]);


            try{
                $category->setParent($this->categoryRepository->find($data["category"]["parent"]));
                $this->getDoctrine()->getManager()->flush();
                return new JsonResponse($category->getParent()->getName());


            }catch (\Exception $exception){

                $this->getDoctrine()->getManager()->flush();
                return new JsonResponse("No Parent");
            }

        }catch (\Exception $ex) {
            return new JsonResponse("error");
        }
    }


    /**
     * @Route("categores", methods={"POST"}, name="createCategories")
     * @param Request $request
     * @return JsonResponse
     */
    public function createCategores(Request $request): Response
    {
        try{
            $data = json_decode($request->getContent(), true);
            $category = new Category();
            $category->deserializer($data["category"]);
            if($data["category"]["parent"] != null)
            {
                $category->setParent($this->categoryRepository->find($data["category"]["parent"]));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return new JsonResponse("ok");
        }catch (\Exception $ex) {
            return new JsonResponse("error");
        }
    }


    /**
     * @Route("categores", methods={"DELETE"}, name="deleteCategories")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCategores(Request $request): Response
    {
        try{
            $data = json_decode($request->getContent(), true);
            $category = $this->categoryRepository->find($data["category"]["id"]);

            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();

            return new JsonResponse("ok");
        }catch (\Exception $ex) {
            return new JsonResponse("error");
        }
    }
}























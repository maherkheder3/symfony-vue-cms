<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Service\PostService;
use App\Utils\Slugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiPostController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var PostService */
    private $postService;

    /**
     * ApiPostController constructor.
     * @param SerializerInterface $serializer
     * @param PostService $postService
     */
    public function __construct(SerializerInterface $serializer, PostService $postService)
    {
        $this->serializer = $serializer;
        $this->postService = $postService;
    }

    /**
     * @Rest\Post("/api/post/create", name="createPost")
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $message = $request->request->get('message');
        $postEntity = $this->postService->createPost($message);
        $data = $this->serializer->serialize($postEntity, 'json');

        return new JsonResponse($data, 200, [], true);
    }



    /**
     * @Rest\Get("/api/post/create", name="getAllPosts")
     * @param Request $request
     * @return JsonResponse
     */
    public function create($request)
    {
        $post = new Post();
//        $post->setAuthor($this->getUser());

        // See https://symfony.com/doc/current/book/forms.html#submitting-forms-with-multiple-buttons
        $form = $this->createForm(PostType::class, $post)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setSlug(Slugger::slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            return new JsonResponse($post, 200, [], true);
        }

        return new JsonResponse($post, 200, [], true);
    }

    /**
     * @Route("/api/posts", defaults={"page": "1", "_format"="html"}, methods={"GET"}, name="getAllPosts")
//     * @Route("/rss.xml", defaults={"page": "1", "_format"="xml"}, methods={"GET"}, name="blog_rss")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods={"GET"}, name="getAllPosts_paginated")
     * @Cache(smaxage="10")
     *
     * @return JsonResponse
     */
    public function posts(Request $request, int $page, string $_format, PostRepository $posts, TagRepository $tags): Response
    {
        $tag = null;
        if ($request->query->has('tag')) {
            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
        }
        $paginator = $posts->findLatest($page, $tag);
//        $paginator = $posts->createPaginator($query, $page);
        $iterator = $paginator->getCurrentPageResults();

        $latestPosts = [];

        foreach ($iterator as $item) {
            $latestPosts[] = $item;
        }

        // Every template name also has two extensions that specify the format and
        // engine for that template.
        // See https://symfony.com/doc/current/templating.html#template-suffix
//        return $this->render('blog/index.'.$_format.'.twig', ['posts' => $latestPosts]);
        return new JsonResponse($latestPosts);
    }
}
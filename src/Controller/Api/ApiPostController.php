<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Class ApiPostController
 * @Route("/api/")
 * @package App\Controller\Api
 */
final class ApiPostController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var PostService */
    private $postService;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var \App\Repository\PostRepository
     */
    private $postRepository;

    /**
     * ApiPostController constructor.
     * @param SerializerInterface $serializer
     * @param PostService $postService
     */
    public function __construct(SerializerInterface $serializer, PostService $postService,
                                UserRepository $userRepository, PostRepository $postRepository)
    {
        $this->serializer = $serializer;
        $this->postService = $postService;
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("post/search/{q}", methods={"GET"}, name="search_post")
     * @param String $q
     * @return JsonResponse
     */
    public function search(string $q): Response
    {
        $iterator = $this->postRepository->createQueryBuilder('p')
            ->Where('p.title LIKE :search')
//            ->andwhere('p.publishedAt <= :now')
            ->setParameter('search', '%' . $q . '%')
            ->orderBy('p.publishedAt', 'DESC')
            ->getQuery()->getResult();

        $latestPosts = [];

        foreach ($iterator as $item) {
            if(sizeof($latestPosts) >= 16)
            {
                break;
            }
            $latestPosts[] = $item->serializer();
        }

        return new JsonResponse($latestPosts);
    }


    /**
     * @Route("posts", defaults={"page": "1", "_format"="html"}, methods={"GET"}, name="getAllPosts")
//     * @Route("/rss.xml", defaults={"page": "1", "_format"="xml"}, methods={"GET"}, name="blog_rss")
     * @Route("/page/{page<[1-9]\d*>}", defaults={"_format"="html"}, methods={"GET"}, name="getAllPosts_paginated")
     * @Cache(smaxage="10")
     *
     * @return JsonResponse
     */
    public function posts(Request $request, int $page, string $_format, TagRepository $tags): Response
    {
//        $tag = null;
//        if ($request->query->has('tag')) {
//            $tag = $tags->findOneBy(['name' => $request->query->get('tag')]);
//        }
//        $paginator = $posts->findLatest($page, $tag);
////        $paginator = $posts->createPaginator($query, $page);
//        $iterator = $paginator->getCurrentPageResults();
//
//        $latestPosts = [];
//
//        foreach ($iterator as $item) {
//            $latestPosts[] = $item;
//        }

        $iterator = $this->postRepository->findLatest($page);

        $latestPosts = [];

        foreach ($iterator as $item) {
            if(sizeof($latestPosts) >= 16)
            {
                break;
            }
            $latestPosts[] = $item->serializer();
        }

        return new JsonResponse($latestPosts);
    }

    /**
     * @Route("post/{post}", methods={"GET"}, name="post_tt_details_id")
     * @param Post $post
     * @return JsonResponse
     */
    public function postShow(Post $post)
    {
        return new JsonResponse($post->serializer());
    }

    /**
     * @Rest\Post("post/create", name="createPost")
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        try{
            $data = json_decode($request->getContent(), true);
            $post = new Post();
            $post->deserializer($data["message"]);

            /** @App\Entity\User $user */
            $user = $this->getUser();
            $post->setAuthor($user);

            $post->setSlug(Slugger::slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash("success", "The new post is created");

            return new JsonResponse("Ok");
        }catch (\Exception $ex) {
            $this->addFlash("alert", "Error - post didn't created");
            return new JsonResponse("Error");
        }
    }

    /**
     * @Rest\Post("post/edit", name="editPost")
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request)
    {
        try{
            $data = json_decode($request->getContent(), true);
            $post = $this->postRepository->find($data["post"]["id"]);
            $post->deserializer($data["post"]);

            /** @App\Entity\User $user */
            $user = $this->getUser();
            $post->setAuthor($user);
            $post->setSlug(Slugger::slugify($post->getTitle()));

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "The post is Updated");

            return new JsonResponse("Ok");
        }catch (\Exception $ex) {
            $this->addFlash("alert", "Error - post didn't created");
            return new JsonResponse("Error");
        }
    }

    /**
     * @Rest\Post("post/imageupload", name="image_upload")
     * @param Request $request
     * @return JsonResponse
     */
    public function imageUpload(Request $request) : Response
    {
        // get file from request and save in the uploads folder
        /** @var UploadedFile $file */
        $file = $request->files->get('image');

        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                'uploads/posts',
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }


        return new JsonResponse($fileName, 200, [], true);
    }

    /**
     * @Route("post/details/{post}", methods={"GET"}, name="post_details")
     * @param Post $post
     * @return JsonResponse
     */
    public function details(Post $post): Response
    {
        return new JsonResponse($post->serializer());
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}
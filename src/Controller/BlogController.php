<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {
    private const POSTS = [
        [
            'id' => 1,
            'slug' => 'hello-you',
            'title' => 'Hello You!',
        ],
        [
            'id' => 2,
            'slug' => 'hi-you',
            'title' => 'Hi You!',
        ],
        [
            'id' => 3,
            'slug' => 'yo-you',
            'title' => 'Yo You!',
        ],
    ];

    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request) {
        $limit = $request->get('limit', 10);

        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
//                'data' => self::POSTS
                'data' => $items,
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     */
//    public function post($id) {
//        return $this->json(
////            self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
//            $this->getDoctrine()->getRepository(BlogPost::class)->find($id)
//        );
//    }
    public function post(BlogPost $post) {
        return $this->json($post);
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug", methods={"GET"})
     */
//    public function postBySlug($slug) {
//        return $this->json(
////            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
//            $this->getDoctrine()->getRepository(BlogPost::class)->findBy(['slug' => $slug])
//        );
//    }
    public function postBySlug(BlogPost $post) {
        return $this->json($post);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request) {

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }

    /**
     * @Route("/post/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete(BlogPost $post) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}

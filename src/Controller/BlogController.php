<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/{page}", name="blog_list")
     */
    public function list($page = 1, Request $request) {
        return $this->json(
            [
                'page' => $page,
                'data' => self::POSTS
            ]
        );
    }

    /**
     * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
     */
    public function post($id) {
        return new JsonResponse(
            self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
        );
    }

    /**
     * @Route("/{slug}", name="blog_by_slug")
     */
    public function postBySlug($slug) {
        return new JsonResponse(
            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
        );
    }
}

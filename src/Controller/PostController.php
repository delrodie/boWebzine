<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Utils\Utilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/article")
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="post_index", methods="GET")
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', ['posts' => $postRepository->findAll()]);
    }

    /**
     * @Route("/new", name="post_new", methods="GET|POST")
     */
    public function new(Request $request, Utilities $utilities): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $slug = $utilities->slugify($post->getTitre());
            $user = $this->getUser()->getUsername();
            $resume = $utilities->resume($post->getContenu(), 300, '...', true);
            $post->setPubliePar($user);
            $post->setSlug($slug);
            $post->setResume($resume); //dump($post);die();

            $em->persist($post);
            $em->flush();

            $this->addFlash('notice', 'Le post a été enregistré avec succès!');

            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="post_show", methods="GET")
     */
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', ['post' => $post]);
    }

    /**
     * @Route("/{slug}/edit", name="post_edit", methods="GET|POST")
     */
    public function edit(Request $request, Post $post, Utilities $utilities): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $utilities->slugify($post->getTitre());
            $user = $this->getUser()->getUsername();
            $resume = $utilities->resume($post->getContenu(), 300, '...', true);
            $post->setModifiePar($user);
            $post->setSlug($slug);
            $post->setResume($resume);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods="DELETE")
     */
    public function delete(Request $request, Post $post): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('post_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('post_index');
    }
}

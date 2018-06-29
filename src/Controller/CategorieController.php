<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Utils\Utilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/categorie")
 */
class CategorieController extends Controller
{
    /**
     * @Route("/", name="backend_categorie_index", methods="GET")
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', ['categories' => $categorieRepository->findAll()]);
    }

    /**
     * @Route("/new", name="backend_categorie_new", methods="GET|POST")
     */
    public function new(Request $request, Utilities $utilities, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $slug = $utilities->slugify($categorie->getLibelle());
            $user = $this->getUser()->getUsername();
            $categorie->setSlug($slug);
            $categorie->setPubliePar($user);

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('notice', 'La catégorie '.$categorie->getLibelle().' a été enregistrée avec succès');

            return $this->redirectToRoute('backend_categorie_new');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'categories'  => $categorieRepository->findAllOrderByAsc(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_categorie_show", methods="GET")
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', ['categorie' => $categorie]);
    }

    /**
     * @Route("/{slug}-{id}/edit", name="backend_categorie_edit", methods="GET|POST")
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository, Utilities $utilities): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $utilities->slugify($categorie->getLibelle());
            $user = $this->getUser()->getUsername();
            $categorie->setSlug($slug);
            $categorie->setModifiePar($user);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'La catégorie '.$categorie->getLibelle().' a été modifiée avec succès!');

            return $this->redirectToRoute('backend_categorie_new');
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
            'categories'    => $categorieRepository->findAllOrderByAsc(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_categorie_delete", methods="DELETE")
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();
        }

        return $this->redirectToRoute('backend_categorie_index');
    }
}

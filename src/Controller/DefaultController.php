<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(){
        return $this->render('frontend/accueil.html.twig');
    }

    /**
     * @Route("/backend", name="backend")
     */
    public function backend(){
        return $this->render('default/dashboard.html.twig');
    }
}
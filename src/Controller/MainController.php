<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion()
    {
        return $this->render('main/connexion.html.twig');
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription()
    {
        return $this->render('main/inscription.html.twig');
    }

    /**
     * @Route("/inscription_professeur", name="inscription_professeur")
     */
    public function inscription_professeur()
    {
        return $this->render('main/inscription_professeur.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        return $this->render('main/search.html.twig');
    }

}

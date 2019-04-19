<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Competence;
use App\Entity\Disponibilite;
use App\Entity\Professeur;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        define('PREFIX_SALT', 'prison');
        define('SUFFIX_SALT', 'break');
        $hashSecure = md5(PREFIX_SALT.'root'.SUFFIX_SALT);
        dump($hashSecure);
        /*
        $hashSecure1 = md5(PREFIX_SALT.'m0tD3P4ss3'.SUFFIX_SALT);
        dump($hashSecure1);
        dump($hashSecure==$hashSecure1);*/
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
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();
        dump($users);

        $repository = $this->getDoctrine()->getRepository(Professeur::class);
        $profs = $repository->findAll();
        dump($profs);

        $repository = $this->getDoctrine()->getRepository(Competence::class);
        $comps = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Disponibilite::class);
        $dispo = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Agenda::class);
        $agendas = $repository->findAll();


        return $this->render('main/search.html.twig');
    }

}

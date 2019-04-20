<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Competence;
use App\Entity\Disponibilite;
use App\Entity\Professeur;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        define('PREFIX_SALT', 'prison');
        define('SUFFIX_SALT', 'break');
        $hashSecure = md5(PREFIX_SALT . 'root' . SUFFIX_SALT);
        dump($hashSecure);
        $tab = (object)array('inputCity' => "", 'inputMat' => "");
        $form = $this->createFormBuilder($tab)
            ->add("inputCity", TextType::class)
            ->add("inputMat", TextType::class)
            ->add("recherche", SubmitType::class)
            ->getForm();
        dump($form->createView());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($tab);
            $repository = $this->getDoctrine()->getRepository(Professeur::class);
            $profs = $repository->allProfFilter($tab->inputMat, $tab->inputCity);
            dump($profs);
            return $this->render('main/search.html.twig',
                array('profs' => $profs, 'matiere' => $tab->inputMat, 'ville' => $tab->inputCity));

        }
        return $this->render('main/index.html.twig', array('form' => $form->createView()));
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
        $repository = $this->getDoctrine()->getRepository(Professeur::class);
        $profs = $repository->allProfFilter("", "");
        dump($profs);

        return $this->render('main/search.html.twig',
            array('profs' => $profs, 'matiere' => "Toutes", 'ville' => "Toutes"));
    }

}

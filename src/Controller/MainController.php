<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Competence;
use App\Entity\Disponibilite;
use App\Entity\Professeur;
use App\Entity\User;
use Composer\Semver\Constraint\Constraint;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

    /**
     * @Route("/remove/{data}/{id}", name="remove")
     */
    public function remove($data,$id)
    {
        dump($data,$id);
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute("home");
        }
        $repository1 = $this->getDoctrine()->getRepository(User::class);
        $repository = $this->getDoctrine()->getRepository(Professeur::class);
        $profData = $repository->findOneBy(["user" => $repository1->findOneBy(["confidental" => $user])]);
        if ($profData == null){
            return $this->redirectToRoute("home");
        }
        if ($data == "competence"){
            $repository = $this->getDoctrine()->getRepository(Competence::class);
            $repository->removeCompetence($id,$profData->getId());
        }

        if ($data == "disponibilite"){
            $repository = $this->getDoctrine()->getRepository(Disponibilite::class);
            $repository->removeDispo($id,$profData->getId());

        }
        return $this->redirectToRoute("profil");
    }


    /**
     * @Route("/profil", name="profil")
     */
    public function profil(Request $request)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute("home");
        }
        $repository1 = $this->getDoctrine()->getRepository(User::class);
        $userData = $repository1->findOneBy(["confidental" => $user]);
        $repository = $this->getDoctrine()->getRepository(Professeur::class);
        $profData = $repository->findOneBy(["user" => $userData]);
        $repository = $this->getDoctrine()->getRepository(Agenda::class);
        $agendaData = $repository->userAgenda($userData->getId());
        $agendaDataProf = null;
        $competenceData = null;
        $dispoData = null;
        if ($profData) {
            $agendaDataProf = $repository->profAgenda($profData->getId());
            $repository = $this->getDoctrine()->getRepository(Competence::class);
            $competenceData = $repository->profCompetence($profData->getId());
            $repository = $this->getDoctrine()->getRepository(Disponibilite::class);
            $dispoData = $repository->profDispo($profData->getId());
        }
        $addCom = (object)array('matiere' => "", 'niveau' => 1, "ajouter");
        $formComp = $this->createFormBuilder($addCom)
            ->add("matiere", ChoiceType::class,
                array("choices" => array(
                    "Français" => "Français",
                    "Mathématiques" => "Mathématiques",
                    "Anglais" => "Anglais",
                    "Espagnol" => "Espagnol",
                    "Italien" => "Italien",
                    "Histoire" => "Histoire",
                    "Géographie" => "Géographie",
                    "Éducation Civique" => "Éducation Civique",
                    "Sciences de la Vie et de la Terre" => "Sciences de la Vie et de la Terre",
                    "Technologie" => "Technologie",
                    "Éducation Musical" => "Éducation Musical",
                    "Art Plastique" => "Art Plastique",
                    "Éducation Physique et Sportive" => "Éducation Physique et Sportive",
                    "Physique" => "Physique",
                    "Chimie" => "Chimie",
                    "Science de l'ingénieur" => "Science de l'ingénieur",
                    "Philosophie" => "Philosophie",
                    "Science Économique" => "Science Économique"
                )))
            ->add("niveau", ChoiceType::class,
                array("choices" => array(
                    "Primaire" => 1,
                    "Collège" => 2,
                    "Lycée" => 3,
                    "Supérieur" => 4
                )))
            ->add("ajouter",SubmitType::class)
            ->getForm();

        $addDispo = (object)array('jour' => 1, 'debut' => "8:00","ajouter");
        $formDispo = $this->createFormBuilder($addDispo)
            ->add("jour", ChoiceType::class,
                array("choices" => array(
                    "Lundi" => 1,
                    "Mardi" => 2,
                    "Mercredi" => 3,
                    "Jeudi" => 4,
                    "Vendredi" => 5,
                    "Samedi" => 6,
                    "Dimanche" => 7
                )))
            ->add("debut", ChoiceType::class,
                array("choices" => array(
                    "8:00" => "8:00",
                    "9:00" => "9:00",
                    "10:00" => "10:00",
                    "11:00" => "11:00",
                    "12:00" => "12:00",
                    "13:00" => "13:00",
                    "14:00" => "14:00",
                    "15:00" => "15:00",
                    "16:00" => "16:00",
                    "17:00" => "17:00",
                    "18:00" => "18:00",
                    "19:00" => "19:00"
                )))
            ->add("ajouter",SubmitType::class)
            ->getForm();
        dump($competenceData,$dispoData,$agendaData);
        $formComp->handleRequest($request);
        if ($formComp->isSubmitted() && $formComp->isValid()) {
            dump($addCom);
            $repository = $this->getDoctrine()->getRepository(Competence::class);
            $repository->insertCompetences($addCom->matiere,$addCom->niveau,$profData->getId());
            return $this->redirectToRoute("profil");
        }
        $formDispo->handleRequest($request);
        if ($formDispo->isSubmitted() && $formDispo->isValid()) {
            dump($addDispo);
            $repository = $this->getDoctrine()->getRepository(Disponibilite::class);
            $repository->insertDispo($addDispo->jour,$addDispo->debut,$profData->getId());
            return $this->redirectToRoute("profil");
        }
        return $this->render('main/profil.html.twig',
            array("user" => $userData,
                "prof" => $profData,
                "agendas" => $agendaData,
                "agendasProf" => $agendaDataProf,
                "compts" => $competenceData,
                "dispos" => $dispoData,
                "formComp" => $formComp->createView(),
                "formDispo" => $formDispo->createView()));
    }

}

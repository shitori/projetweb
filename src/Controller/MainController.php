<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\Competence;
use App\Entity\Disponibilite;
use App\Entity\Professeur;
use App\Entity\User;
use App\Entity\Usersecurity;
use Composer\Semver\Constraint\Constraint;
use phpDocumentor\Reflection\Types\This;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use function Sodium\add;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Professeur::class);
            $profs = $repository->allProfFilter($tab->inputMat, $tab->inputCity);
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
        return $this->render('main/search.html.twig',
            array('profs' => $profs, 'matiere' => "Toutes", 'ville' => "Toutes"));
    }

    /**
     * @Route("/remove/{data}/{id}", name="remove")
     */
    public function remove($data, $id)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute("home");
        }
        $repository1 = $this->getDoctrine()->getRepository(User::class);
        $userData = $repository1->findOneBy(["confidental" => $user]);
        $repository = $this->getDoctrine()->getRepository(Professeur::class);
        $profData = $repository->findOneBy(["user" => $repository1->findOneBy(["confidental" => $user])]);
        if ($data == "agenda"){
            $repository = $this->getDoctrine()->getRepository(Agenda::class);
            $repository->removeAgenda($id,$userData->getId());
            return $this->redirectToRoute("profil");
        }
        if ($profData == null) {
            return $this->redirectToRoute("home");
        }
        if ($data == "competence") {
            $repository = $this->getDoctrine()->getRepository(Competence::class);
            $repository->removeCompetence($id, $profData->getId());
        }

        if ($data == "disponibilite") {
            $repository = $this->getDoctrine()->getRepository(Disponibilite::class);
            $repository->removeDispo($id, $profData->getId());
        }
        return $this->redirectToRoute("profil");
    }

    /**
     * @Route("/profil/{id}", name="information")
     */
    public function information($id, Request $request, ObjectManager $manager)
    {
        $repository = $this->getDoctrine()->getRepository(Usersecurity::class);
        $confidentialData = $repository->find($id);
        if ($confidentialData == null) {
            return $this->redirectToRoute("home");
        }
        $repository1 = $this->getDoctrine()->getRepository(User::class);
        $userData = $repository1->findOneBy(["confidental" => $confidentialData]);
        $repository = $this->getDoctrine()->getRepository(Professeur::class);
        $profData = $repository->findOneBy(["user" => $userData]);
        $agendaDataProf = null;
        $competenceData = null;
        $dispoData = null;
        $form = null;
        $allprofDispo = array();
        $allprofDispoDay = array();
        if ($profData) {
            $repository = $this->getDoctrine()->getRepository(Agenda::class);
            $agendaDataProf = $repository->profAgenda($profData->getId());
            $repository = $this->getDoctrine()->getRepository(Competence::class);
            $competenceData = $repository->profCompetence($profData->getId());
            $repository = $this->getDoctrine()->getRepository(Disponibilite::class);
            $dispoData = $repository->profDispo($profData->getId());

            foreach ($dispoData as $dispo) {
                $allprofDispo[$dispo["debut"]] = $dispo["debut"];
                switch ($dispo["jour"]) {
                    case 1:
                        $allprofDispoDay["Lundi"] = $dispo["jour"];
                        break;
                    case 2:
                        $allprofDispoDay["Mardi"] = $dispo["jour"];
                        break;
                    case 3:
                        $allprofDispoDay["Mercredi"] = $dispo["jour"];
                        break;
                    case 4:
                        $allprofDispoDay["Jeudi"] = $dispo["jour"];
                        break;
                    case 5:
                        $allprofDispoDay["Vendredi"] = $dispo["jour"];
                        break;
                    case 6:
                        $allprofDispoDay["Samedi"] = $dispo["jour"];
                        break;
                    case 7:
                        $allprofDispoDay["Dimanche"] = $dispo["jour"];
                        break;
                }
            }
            $addAgenda = (object)array('jour' => 1, 'motif' => "", 'debut' => "8:00", "date" => "cette semaine", "ajouter");
            $form = $this->createFormBuilder($addAgenda)
                ->add("date", ChoiceType::class,
                    array("choices" => array(
                        "Cette semaine" => 0,
                        "Dans une semaine" => 7,
                        "Dans deux semaines" => 14,
                        "Dans trois semaines" => 21,
                        "Dans un mois" => 28
                    )))
                ->add("jour", ChoiceType::class,
                    array("choices" => $allprofDispoDay))
                ->add("debut", ChoiceType::class,
                    array("choices" => $allprofDispo))
                ->add("motif", TextareaType::class)
                ->add("ajouter", SubmitType::class)
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $userConnect = $this->getUser();
                $repository1 = $this->getDoctrine()->getRepository(Disponibilite::class);
                $horairePossible = $repository1->findHorairePossible(
                    $profData->getId(), $addAgenda->debut, $addAgenda->jour);

                if (sizeof($horairePossible) == 0) {
                    return $this->redirectToRoute("information", ["id" => $id]);
                }

                if ($userConnect == null) {
                    return $this->redirectToRoute("app_login");
                }

                $repository1 = $this->getDoctrine()->getRepository(User::class);
                $userConnectData = $repository1->findOneBy(["confidental" => $userConnect]);
                $today = 0;
                switch (date('l')) {
                    case "Monday":
                        $today = 1;
                        break;
                    case "Tuesday":
                        $today = 2;
                        break;
                    case "Wednesday":
                        $today = 3;
                        break;
                    case "Thursday":
                        $today = 4;
                        break;
                    case "Friday":
                        $today = 5;
                        break;
                    case "Saturday":
                        $today = 6;
                        break;
                    case "Sunday":
                        $today = 7;
                        break;
                }
                $ope = intval($addAgenda->jour) + intval($addAgenda->date) - $today;
                $operation = '+' . $ope . 'days';
                $date = date('Y-m-d', strtotime($operation));
                $repository1 = $this->getDoctrine()->getRepository(Agenda::class);
                $horairePris = $repository1->noPlace($addAgenda->debut, $addAgenda->debut, $profData->getId());
                print_r ($horairePris);
                if (sizeof($horairePris) > 0) {
                    return $this->redirectToRoute("information", ["id" => $id]);
                }
                $dt = new DateTime($addAgenda->debut);
                $dt_bis = new DateTime($addAgenda->debut);
                $agenda = new Agenda();
                $agenda->setDatep(new DateTime($date));
                $agenda->setDebut($dt_bis);
                $agenda->setFin($dt->modify('+ 1 hour'));
                $agenda->setRaison($addAgenda->motif);
                $agenda->setUser($userConnectData);
                $agenda->setProf($profData);
                $agenda->setJour($addAgenda->jour);
                $manager->persist($agenda);
                $manager->flush();
                return $this->redirectToRoute("profil");
            }
        }
        if ($form && sizeof($allprofDispo) > 0 && sizeof($allprofDispoDay) > 0) {
            return $this->render('main/information.html.twig',
                array("user" => $userData,
                    "prof" => $profData,
                    "agendasProf" => $agendaDataProf,
                    "compts" => $competenceData,
                    "dispos" => $dispoData,
                    "confidential" => $confidentialData,
                    "form" => $form->createView()));
        }
        return $this->render('main/information.html.twig',
            array("user" => $userData,
                "prof" => $profData,
                "agendasProf" => $agendaDataProf,
                "compts" => $competenceData,
                "dispos" => $dispoData,
                "confidential" => $confidentialData,
                "form" => $form));
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
            ->add("ajouter", SubmitType::class)
            ->getForm();

        $addDispo = (object)array('jour' => 1, 'debut' => "8:00", "ajouter");
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
            ->add("ajouter", SubmitType::class)
            ->getForm();
        $formComp->handleRequest($request);
        if ($formComp->isSubmitted() && $formComp->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Competence::class);
            $repository->insertCompetences($addCom->matiere, $addCom->niveau, $profData->getId());
            return $this->redirectToRoute("profil");
        }
        dump($agendaData,$agendaDataProf);
        $formDispo->handleRequest($request);
        if ($formDispo->isSubmitted() && $formDispo->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Disponibilite::class);
            $repository->insertDispo($addDispo->jour, $addDispo->debut, $profData->getId());
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

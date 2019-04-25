<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Entity\User;
use App\Entity\Usersecurity;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $encoder,
                             ObjectManager $manager): Response
    {
        $usersecurity = new Usersecurity();
        $user = new User();
        $error = false;
        $data = (object)array(
            "inputEmail" => "",
            "inputLastName" => "",
            "inputFirstName" => "",
            "inputPass" => "",
            "inputPassConfirm" => "",
            "inputSexe" => "",
            "inputCity" => "",
            "inputPhone" => "",
            "inputBd" => new DateTime());
        $form = $this->createFormBuilder($data)
            ->add('inputEmail', EmailType::class)
            ->add('inputLastName', TextType::class)
            ->add('inputFirstName', TextType::class)
            ->add('inputPass', PasswordType::class)
            ->add('inputPassConfirm', PasswordType::class)
            ->add('inputSexe', ChoiceType::class, array('choices' => array("Homme" => 0, "Femme" => 1)))
            ->add('inputCity', TextType::class)
            ->add('inputPhone', TextType::class)
            ->add('inputBd', BirthdayType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Usersecurity::class);

            if (filter_var($data->inputEmail, FILTER_VALIDATE_EMAIL)
                && $data->inputPass == $data->inputPassConfirm
                && $repository->findOneBy(["email" => $data->inputEmail]) == null) {
                $usersecurity->setEmail($data->inputEmail);
                $usersecurity->setPassword($data->inputPass);
                $hash = $encoder->encodePassword($usersecurity, $usersecurity->getPassword());
                $usersecurity->setPassword($hash);
                $manager->persist($usersecurity);
                $manager->flush();
                $repository = $this->getDoctrine()->getRepository(Usersecurity::class);
                $actualuser = $repository->findOneBy(["email" => $data->inputEmail]);
                $user->setBirthday($data->inputBd);
                $user->setConfidental($actualuser);
                $user->setNom($data->inputLastName);
                $user->setPhone($data->inputPhone);
                $user->setPrenom($data->inputFirstName);
                $user->setSexe($data->inputSexe);
                $user->setVille($data->inputCity);
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute("app_login");
            }
            $error = true;
        }
        return $this->render('security/register.html.twig',
            array("form" => $form->createView(), "error" => $error));
    }

    /**
     * @Route("/register_prof", name="app_register_prof")
     */
    public function registerProf(Request $request,
                                 UserPasswordEncoderInterface $encoder,
                                 ObjectManager $manager): Response
    {
        $usersecurity = new Usersecurity();
        $user = new User();
        $prof = new Professeur();
        $error = false;
        $data = (object)array(
            "inputEmail" => "",
            "inputLastName" => "",
            "inputFirstName" => "",
            "inputPass" => "",
            "inputPassConfirm" => "",
            "inputSexe" => "",
            "inputCity" => "",
            "inputPhone" => "",
            "inputBd" => new DateTime(),
            "inputAddr" => "",
            "inputPrice" => 0);
        $form = $this->createFormBuilder($data)
            ->add('inputEmail', EmailType::class)
            ->add('inputLastName', TextType::class)
            ->add('inputFirstName', TextType::class)
            ->add('inputPass', PasswordType::class)
            ->add('inputPassConfirm', PasswordType::class)
            ->add('inputSexe', ChoiceType::class, array('choices' => array("Homme" => 0, "Femme" => 1)))
            ->add('inputCity', TextType::class)
            ->add('inputPhone', TextType::class)
            ->add('inputBd', BirthdayType::class)
            ->add('inputAddr', TextType::class)
            ->add('inputPrice', NumberType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Usersecurity::class);
            if (filter_var($data->inputEmail, FILTER_VALIDATE_EMAIL)
                && $data->inputPass == $data->inputPassConfirm
                && $repository->findOneBy(["email" => $data->inputEmail] == null)) {
                $usersecurity->setEmail($data->inputEmail);
                $usersecurity->setPassword($data->inputPass);
                $hash = $encoder->encodePassword($usersecurity, $usersecurity->getPassword());
                $usersecurity->setPassword($hash);
                $manager->persist($usersecurity);
                $manager->flush();
                $repository = $this->getDoctrine()->getRepository(Usersecurity::class);
                $actualuser = $repository->findOneBy(["email" => $data->inputEmail]);
                $user->setBirthday($data->inputBd);
                $user->setConfidental($actualuser);
                $user->setNom($data->inputLastName);
                $user->setPhone($data->inputPhone);
                $user->setPrenom($data->inputFirstName);
                $user->setSexe($data->inputSexe);
                $user->setVille($data->inputCity);
                $manager->persist($user);
                $manager->flush();
                $repository = $this->getDoctrine()->getRepository(User::class);
                $actualuser = $repository->findOneBy(["confidental" => $actualuser]);
                $prof->setUser($actualuser);
                $prof->setAdresse($data->inputAddr);
                $prof->setPrix($this->floatValue($data->inputPrice));
                $manager->persist($prof);
                $manager->flush();
                return $this->redirectToRoute("app_login");
            }
            $error = true;
        }
        return $this->render('security/register_prof.html.twig',
            array("form" => $form->createView(), "error" => $error));
    }

    /**
     * @Route("/modifer_professeur", name="app_modifer_prof")
     */
    public function modifierProf(Request $request)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute("home");
        }
        $repository1 = $this->getDoctrine()->getRepository(User::class);
        $userData = $repository1->findOneBy(["confidental" => $user]);
        $repository = $this->getDoctrine()->getRepository(Professeur::class);
        $profData = $repository->findOneBy(["user" => $repository1->findOneBy(["confidental" => $user])]);
        if ($profData == null) {
            return $this->redirectToRoute("home");
        }
        $data = (object)array(
            "inputAddr" => "",
            "inputPrice" => 0);
        $form = $this->createFormBuilder($data)
            ->add('inputAddr', TextType::class)
            ->add('inputPrice', NumberType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository1 = $this->getDoctrine()->getRepository(Professeur::class);
            $repository1->modif($data->inputAddr,
                $this->floatValue($data->inputPrice),
                $profData->getId());
            return $this->redirectToRoute("profil");
        }
        return $this->render('security/modifier_prof.html.twig',
            array("form" => $form->createView(), "info" => $profData));
    }


    /**
     * @Route("/modifer", name="app_modifer")
     */
    public function modifier(Request $request)
    {
        $user = $this->getUser();
        if ($user == null) {
            return $this->redirectToRoute("home");
        }
        $repository1 = $this->getDoctrine()->getRepository(User::class);
        $userData = $repository1->findOneBy(["confidental" => $user]);
        $data = (object)array(
            "inputLastName" => "",
            "inputFirstName" => "",
            "inputSexe" => "",
            "inputCity" => "",
            "inputPhone" => "",
            "inputBd" => new DateTime());
        $form = $this->createFormBuilder($data)
            ->add('inputLastName', TextType::class)
            ->add('inputFirstName', TextType::class)
            ->add('inputSexe', ChoiceType::class, array('choices' => array("Homme" => 0, "Femme" => 1)))
            ->add('inputCity', TextType::class)
            ->add('inputPhone', TextType::class)
            ->add('inputBd', BirthdayType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository1 = $this->getDoctrine()->getRepository(User::class);
            $repository1->modif($data->inputLastName,
                $data->inputFirstName,
                $data->inputSexe,
                $data->inputCity,
                $data->inputPhone,
                $data->inputBd,
                $userData->getId());
            return $this->redirectToRoute("profil");
        }
        return $this->render('security/modifier.html.twig',
            array("form" => $form->createView(), "info" => $userData));
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    public function floatValue($str)
    {
        if (preg_match("/([0-9\.,-]+)/", $str, $match)) {
            $value = $match[0];
            if (preg_match("/(\.\d{1,2})$/", $value, $dot_delim)) {
                $value = (float)str_replace(',', '', $value);
            } else if (preg_match("/(,\d{1,2})$/", $value, $comma_delim)) {
                $value = str_replace('.', '', $value);
                $value = (float)str_replace(',', '.', $value);
            } else
                $value = (int)$value;
        } else {
            $value = 0;
        }
        return $value;
    }
}

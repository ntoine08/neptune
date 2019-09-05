<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\RegistrationType;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration") //fonction appeler quand on emprunte cette route
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et valide
            $hash = $encoder->encodePassword($user, $user->getMdp());

            $user->setMdp($hash);

            $manager->persist($user); // pour faire persisté dans le temps le user
            $manager->flush(); //pour le sauvegarder

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(){
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}

    /**
     * @Route("/administration", name="security_admin")
     */
    public function admin(ArticleRepository $repo){
        $articles = $repo->findAll();


        return $this->render('security/admin.html.twig', [
            'controller_name' => 'SiteController',
            'articles' => $articles,
                   
        ]);       
    }
}

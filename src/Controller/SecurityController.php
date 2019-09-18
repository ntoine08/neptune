<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Form\RegistrationType;

use App\Repository\UserRepository;
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
     * @Route("/administration/{page<\d+>?1}", name="security_admin")
     */
    public function admin(ArticleRepository $repo, UserRepository $userRepo, $page){
        $limit = 10;

        $start = $page * $limit - $limit; // 1 * 10 = 10 - 10 = 0 / 2 * 10 = 20 - 10 = 10 donc je part du 10eme article

        $articles = $repo->findBy([], [], $limit, $start);

        $total = count($repo->findAll());
        $pages = ceil($total / $limit); 

        $limits = 10;

        $starts = $page * $limits - $limits;

        $users = $userRepo->findBy([], [], $limits, $starts);

        $totals = count($repo->findAll());
        $pagess = ceil($totals / $limits);

        
        
        return $this->render('security/admin.html.twig', [
            'controller_name' => 'SiteController',
            'articles' => $articles,
            'users' => $users, 
            'pagess' => $pagess, 
            'pages' => $pages,
            'page' => $page   
        ]);       
    }
}

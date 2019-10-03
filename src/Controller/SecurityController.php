<?php

namespace App\Controller; //le contrôleur est une fonction PHP qui est crée et retourne un objet response 

use App\Entity\User;
use App\Entity\Article;
use App\Form\RegistrationType;

use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

        $form->handleRequest($request); // pour traiter les données du formulaire

        if($form->isSubmitted() && $form->isValid()) { //si le formulaire est soumis et valide
            $hash = $encoder->encodePassword($user, $user->getMdp());// encoder le mot de passe

            $user->setMdp($hash);

            $manager->persist($user); // pour faire persisté dans le temps le user
            $manager->flush(); //pour le sauvegarder
            //redirection une fois l'inscription faite
            return $this->redirectToRoute('security_login');
        }
        // permet de rendre le modèle
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
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function admin(ArticleRepository $repo, UserRepository $userRepo, $page){
        $limit = 10;

        $start = $page * $limit - $limit; // 1 * 10 = 10 - 10 = 0 / 2 * 10 = 20 - 10 = 10 donc je part du 10eme article
        //premier [] ne rien rechercher seulement faire reonter les infos 2eme [] pour ordonner mais laisse vide pas important
        //pour ce que je souhaite
        $articles = $repo->findBy([], [], $limit, $start); 

        $total = count($repo->findAll());
        $pages = ceil($total / $limit); 

        $limits = 10;

        $starts = $page * $limits - $limits;

        $users = $userRepo->findBy([], [], $limits, $starts);

        $totals = count($userRepo->findAll());
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

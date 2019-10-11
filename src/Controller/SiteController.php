<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditType;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\TextareaType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UserRepository;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SiteController extends AbstractController
{
    /**
     * @Route("/site/{page<\d+>?1}", name="site")
     */
    public function index(ArticleRepository $repo, $page) //fonction pour afficher les actualités
    {
        $limit = 5;

        $start = $page * $limit - $limit;

        $articles = $repo->findBy([], [], $limit, $start);
        $total = count($repo->findAll());// count pour compter tout les éléments

        $pages = ceil($total / $limit);// ceil pour arondir au nombre supérieur

        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
            'articles' => $articles,
            'pages' => $pages,
            'page' => $page  
        ]);
    }

    /**
     *  @Route("/article/{page<\d+>?1}", name="article")
     */
    public function article(ArticleRepository $repo, $page) // fonction pour afficher les articles
    {
        $limit = 5;

        $start = $page * $limit - $limit;

        $articles = $repo->findBy([], [], $limit, $start);
        $total = count($repo->findAll());
        $pages = ceil($total / $limit);
        
        return $this->render('site/article.html.twig', [
            'controller_name' => 'SiteController',
            'articles' => $articles,
            'pages' => $pages,
            'page' => $page  
        ]);
    }

    /**
     * @Route("/site/new", name="site_create")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) {
        
        //fonction pour crée un article
        if(!$article) {
            $article = new Article();
        }
        
        $form = $this->createForm(ArticleType::class, $article);

        $article->setDateArticle(new \DateTime()); // pour mettre la date ou l'article est crée
                

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $imagee = $form->get('imageArticle')->getData();// pour récupéré les données de l'image
                if($imagee != NULL) {
                    $namee = $article->getId().''.mt_rand(0,1000000);// donne un numéro au hasard a l'image quand elle est envoyer dans le dossier
                    $imageNamee = $namee.'.'. $imagee->guessExtension();// récupère l'extension du fichier
                    $imagee->move( // déplacé l'image
                        $this->getParameter('image_directory'), // récupéré les paramètres
                        $imageNamee
                    );
                    $article->setImageArticle($imageNamee);
                } else {
                    $article->setImageArticle('marseille.jpg');
                }

            $imagee2 = $form->get('image2Article')->getData();
                if($imagee2 != NULL) {
                    $namee2 = $article->getId().''.mt_rand(0,1000000);
                    $image2Namee = $namee2.'.'. $imagee2->guessExtension();
                    $imagee2->move(
                        $this->getParameter('image2_directory'),
                        $image2Namee
                    );
                    $article->setImage2Article($image2Namee);
                } else {
                    $article->setImage2Article('marseille.jpg');
                }

            $manager->persist($article); //gardé
            $manager->flush(); //envoyé
            $this->addFlash('success', 'L\'article a bien été enregistré !');
            //return $this->redirectToRoute('site', ['id' => $article->getId()]);
        }
        
        return $this->render('site/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null,
        ]);   
    }

    /**
     * @Route("/site/{id}/edit", name="site_edit")
     */
    public function edit(Article $article = null, Request $request, ObjectManager $manager) { // pour modifier l'article
            
        if(!$article) {
            $article = new Article();
        }
        
        $form = $this->createForm(EditType::class, $article);

        $article->setDateArticle(new \DateTime());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {   

            $manager->persist($article);
            $manager->flush();
            $this->addFlash('success', 'L\'article a bien été modifié !');
        }
        

        return $this->render('site/edit.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null,
        ]);
        
        }

     /**
      * @Route("/article/remove/{id}", name="removeArticle")
      */

    public function remove ($id, ObjectManager $manager)// pour supprimer l'article
        {
            $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
    
                $manager->remove($article);
                $manager->flush();
    
                $this->addFlash('success', 'L\'article a bien été supprimé !');
                // nom du path pour renvoyer
            return $this->redirectToRoute('security_admin');
        }

    /**
      * @Route("/user/remove/{id}", name="removeUser")
      */

      public function removeUser ($id, ObjectManager $manager) // pour supprimer un utilisateur
      {
          $user = $this->getDoctrine()->getRepository(User::class)->find($id); // avoir l'id de l'utilisateur qu'on va supprimer
  
              $manager->remove($user); //pour supprimer
              $manager->flush(); // valider
  
              $this->addFlash('success', 'La personne a bien été supprimé !');
          return $this->redirectToRoute('security_admin');
      }

    /**
      * @Route("/commentaire/remove/{id}", name="removeCommentaire")
      * @Security("is_granted('ROLE_ADMIN')")
      */
      //pour supprimer les commentaires
      public function removeCommentaire (Commentaire $commentaire, ObjectManager $manager) // pour supprimer un commentaire
      {
        //   $commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->find($id);
              $article = $commentaire->getArticle();
              $manager->remove($commentaire);
              $manager->flush();
  
              $this->addFlash('success', 'Le commentaire a bien été supprimé !');
          return $this->redirectToRoute('site_show', ['id' => $article->getId()]);
      }
    

    /**
     * @Route("/site{id}", name="site_show")
     */ 
    // afficher l'article en entier et afficher les commentaires
    public function show(Article $article, Request $request, ObjectManager $manager){
        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentaire->setDateCommentaire(new \DateTime())
                        ->setUser($this->getUser()) // récupéré le user connecté et le définir sur le commentaire
                        ->setArticle($article);
            $manager->persist($commentaire);
            $manager->flush();

            return $this->redirectToRoute('site_show', ['id' => $article->getId()]); // retourné à l'article ou on a mi le commentaire
        }      
        return $this->render('site/show.html.twig', [
            'article' => $article,
            'commentaireForm' => $form->createView()
        ]);
    }

    // fonction pour rediriger sur la page home
    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('site/home.html.twig');
    }

    /**
     * @Route("/àPropos", name="propos")
     */
    public function propos() {
        return $this->render('site/propos.html.twig');
    }

    /**
     * @Route("/reglement", name="reglement")
     */
    public function reglement() {
        return $this->render('site/reglement.html.twig');
    }
}

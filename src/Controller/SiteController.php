<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\EditType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class SiteController extends AbstractController
{
    /**
     * @Route("/site", name="site")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
            'articles' => $articles
        ]);
    }

    /**
     *  @Route("/article", name="article")
     */
    public function article(ArticleRepository $repo)
    {
        $articles = $repo->findAll();

        return $this->render('site/article.html.twig', [
            'controller_name' => 'SiteController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/site/new", name="site_create")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) {
        
        
        if(!$article) {
            $article = new Article();
        }
        
        $form = $this->createForm(ArticleType::class, $article);

        $article->setDateArticle(new \DateTime());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
            $imagee = $form->get('imageArticle')->getData();
                if($imagee != NULL) {
                    $namee = $article->getId().''.mt_rand(0,1000000);
                    $imageNamee = $namee.'.'. $imagee->guessExtension();
                    $imagee->move(
                        $this->getParameter('image_directory'),
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

            $manager->persist($article);
            $manager->flush();

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
    public function edit(Article $article = null, Request $request, ObjectManager $manager) {
            
        if(!$article) {
            $article = new Article();
        }
        
        $form = $this->createForm(EditType::class, $article);

        $article->setDateArticle(new \DateTime());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {   

            $manager->persist($article);
            $manager->flush();
        }

        return $this->render('site/edit.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null,
        ]);
        
        }
    /**
     * @Route("/site/{id}", name="site_show")
     */
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

            return $this->redirectToRoute('site_show', ['id' => $article->getId()]);
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



    
}

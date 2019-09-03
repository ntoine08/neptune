<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/site/{id}/edit", name="site_edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) {
        
        if(!$article) {
            $article = new Article();
        }
        

        $form = $this->createForm(ArticleType::class, $article);

        $article->setDateArticle(new \DateTime());
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           

            $manager->persist($article);
            $manager->flush();

            //return $this->redirectToRoute('site', ['id' => $article->getId()]);
        }

        return $this->render('site/create.html.twig', [
            'formArticle' => $form->createView()
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

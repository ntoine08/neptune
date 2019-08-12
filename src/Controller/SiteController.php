<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

    // fonction pour rediriger sur la page home
    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render('site/home.html.twig');
    }

    /**
     * @Route("/site/new", name="site_create")
     */
    public function create(Request $request, ObjectManager $manager) {
        $article = new Article();

        $form = $this->createFormBuilder($article)
                     ->add('titreArticle', TextType::class, [
                         'attr' => [
                             'placeholder' => "Titre de l'article"
                         ]
                     ])
                     ->add('contenuArticle', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "Contenu de l'article"
                         ]
                     ])
                     ->add('imageArticle', TextType::class, [
                         'attr' => [
                             'placeholder' => "Image de l'article"
                         ]
                     ])
                     ->add('titre2Article', TextType::class, [
                         'attr' => [
                             'placeholder' => "2eme Titre (optionnel)"
                         ]
                     ])
                     ->add('contenu2Article', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "2eme Paragraphe (optionnel)"
                         ]
                     ])
                     ->add('image2Article', TextType::class, [
                         'attr' => [
                             'placeholder' => "2eme Image (optionnel)"
                         ]
                     ])
                     ->add('titre3Article', TextType::class, [
                         'attr' => [
                             'placeholder' => "3eme Titre (optionnel)"
                         ]
                     ])
                     ->add('contenu3Article', TextareaType::class, [
                         'attr' => [
                             'placeholder' => "3eme Paragraphe (optionnel)"
                         ]
                     ])
                     ->getForm();

        return $this->render('site/create.html.twig', [
            'formArticle' => $form->createView()
        ]);
    }

    
}

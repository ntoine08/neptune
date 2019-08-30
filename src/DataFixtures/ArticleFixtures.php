<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;


class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <=10; $i++){
            $article = new Article();
            $article->setTitreArticle("Titre de l'article n°$i")
                    ->setContenuArticle("<p>Contenu de l'article n°$i</p>")
                    ->setImageArticle("http://placehold.it/700x300")
                    ->setDateArticle(new \DateTime())
                    ->setCategory(new \Category());

            $manager->persist($article);
        }

        $manager->flush();
    }
}

<?php
namespace App\Tests\Entity;
use App\Entity\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testTitreArticle()
    {
        $article = new Article();
        $titre = "Test 2";
        
        $article->setTitreArticle($titre);
        $this->assertEquals("Test 2", $article->getTitreArticle());
    }
}

?>
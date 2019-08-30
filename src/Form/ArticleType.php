<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreArticle')
            ->add('contenuArticle')
            ->add('imageArticle')
            ->add('titre2Article')
            ->add('contenu2Article')
            ->add('image2Article')
            ->add('titre3Article')
            ->add('contenu3Article')
            ->add('category', EntityType::class, [
                        'class' => Category::class,
                        'choice_label' => "nomCategory"
                        ])
            ->add('nonArticle')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

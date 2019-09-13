<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titreArticle')
            ->add('contenuArticle', TextareaType::class)
            ->add('titre2Article')
            ->add('contenu2Article', TextareaType::class)
            ->add('titre3Article')
            ->add('contenu3Article', TextareaType::class)
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

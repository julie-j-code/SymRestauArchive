<?php

namespace App\Form;

use App\Entity\Menu;
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
            ->add('title')
            //attention, Menu est une relation...
            ->add('Menu', EntityType::class, [
                'class'=> Menu::class,
                'choice_label'=> 'title'
            ])
            //attention, Category est une relation...
            ->add('Category', EntityType::class, [
                'class'=> Category::class,
                'choice_label' => function(?Category $category) {
                    return $category ? strtoupper($category->getName()) : [];
                }
            ])
            ->add('content')
            ->add('allergen')
           // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

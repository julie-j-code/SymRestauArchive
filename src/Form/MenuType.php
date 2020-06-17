<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Article;
// use App\Form\ArticleType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('image')
            ->add('createdAt')
            ->add('endDate')
            ->add('price')
            //attention, articles est une relation...

            // Test CollectionType ci-dessous. 
            // Convient pour renseigner et enregistrer un article en même temps qu'un menu
            // ->add('articles', CollectionType::class, [
            //options supplémentaires suite aux tests de validation du formulaire
            // 'entry_type' => ArticleType::class,
            // 'allow_add' => true,
            //'allow_delete' => true,
            //'prototype' => true,
            //'by_reference' => false
            ->add('articles', EntityType::class, [
            'class'=> Article::class,
            'choice_label'=> 'title',
            'multiple' => true

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }
}

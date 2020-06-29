<?php

namespace App\Form;

//use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required'=> true
            ])
            ->add('username')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'utilisateur' => 'ROLE_USER',
                    'editeur' => 'ROLE_EDITOR',
                    'modérateur' => 'ROLE_MODO',
                    'administrateur' => 'ROLE_ADMIN'
                ], 
                'expanded' => true,
                'multiple' => true,
                'label' => 'userRoles'
            ]) 

/*             ->add('role', EntityType::class, [
                'class' => Role::class,
                'expanded' => true,
                // quelle propriété est utilisée pour le label
                'label' => 'title',
                'multiple' => true
            ]) */

            // géré comme tous dans le rendu twig
           // ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}

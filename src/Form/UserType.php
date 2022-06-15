<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, array('label'=>'Nom', 'attr'=>array('class'=>'form-control form-group')))
            ->add('prenom',TextType::class, array('label'=>'Prenom', 'attr'=>array('class'=>'form-control form-group')))
            ->add('email', EmailType::class, array('label'=>'Email', 'attr'=>array('class'=>'form-control form-group')))
            ->add('password', PasswordType::class, array('label'=>'Mot de Passe', 'attr'=>array('class'=>'form-control form-group')))
            // ->add('roles', EntityType::class, array('class'=>ChoiceType::class, 'label'=>'Categorie', 'attr'=>array('class'=>'form-control form-group')))
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'choices'  => [
                    'User' => 'ROLE_USER',
                    'Gestionnaire' => 'ROLE_GESTION_STOCK',
                    'Admin' => 'ROLE_ADMIN',
                    'Vendeur' => 'ROLE_VENDEUR',
                  ],
                'label'=>'Roles', 
                'attr'=>array('class'=>'form-control form-group')
                
            ])->add('Valider', SubmitType::class, array('attr'=>array('class'=> 'btn btn-success')))
            ;
            
            $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    return $tagsAsArray;
                },
                function ($tagsAsString) {
                    return $tagsAsString;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

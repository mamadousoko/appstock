<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('id', TextType::class, array('label'=>'Id du Produit', 'attr'=>array('class'=>'form-control form-group')))
            ->add('libelle', TextType::class, array('label'=>'Libelle du Produit', 'attr'=>array('class'=>'form-control form-group')))
            ->add('stock', HiddenType::class, array('label'=>'Stock du Produit','attr'=>array('class'=>'form-control form-group', 'value'=>'0')))
            ->add('categorie', EntityType::class, array('class'=>Categorie::class, 'label'=>'Categorie', 'attr'=>array('class'=>'form-control form-group')))
           // ->add('user', EntityType::class, array('class'=>User::class, 'label'=>'user', 'attr'=>array('class'=>'form-control form-group')))
            ->add('Valider', SubmitType::class, array('attr'=>array( 'class'=> 'btn btn-success')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

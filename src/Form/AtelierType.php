<?php

namespace App\Form;

use App\Entity\Atelier;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AtelierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('nomAtelier', TextType::class)
            ->add('programmer' , EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => function ($categorie) {
                    return $categorie->getNomCategorie(); 
                }
            ])

            ->add('submit', SubmitType::class, ['label'=>'Créer', 'attr'=>['class'=>'btn-primary btn-block']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Atelier::class,
        ]);
    }
}
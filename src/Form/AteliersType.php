<?php

namespace App\Form;

use App\Entity\Duree;
use App\Entity\Atelier;
use App\Form\DureeType;
use App\Entity\Categorie;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AteliersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('duree', CollectionType::class, [
                'label' => false,
                'entry_type'=> DureeType::class,
                'entry_options'=>[
                    'label'=>"Atelier et Duree : "
                ],
                'allow_add'=> true,
                'allow_delete'=> true,
                'by_reference' => false

            ])
           
            ->add('submit', SubmitType::class, ['label'=>'Valider', 'attr'=>['class'=>'btn-primary btn-block']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
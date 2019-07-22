<?php

namespace App\Form;

use App\Entity\Duree;
use App\Entity\Atelier;
use App\Entity\Categorie;
use App\Entity\Formation;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DureeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('ateliers', EntityType::class,[
            'class' => Atelier::class,
            'label' =>false,
            'query_builder' => function (EntityRepository $er){
                return $er->createQueryBuilder('a')
                ->orderBy('a.nomAtelier','ASC');
            },

            
            
            ])
         ->add('nbJour');
           
    
}
                   

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Duree::class,
        ]);
    }
}

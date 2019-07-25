<?php

namespace App\Form;

use App\Entity\Posseder;
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

class PossederType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('materiels', EntityType::class,[
            'class' => Materiel::class,
            'label' =>false,
            'query_builder' => function (EntityRepository $er){
                return $er->createQueryBuilder('m')
                ->orderBy('m.Intitule','ASC');
            },

            
            
            ])
         ->add('quantite', IntegerType::class);
           
    
}
                   

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Duree::class,
        ]);
    }
}

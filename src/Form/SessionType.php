<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenir', EntityType::class, [              
                    'class' => Formation::class,
                    'choice_label' => 'nomFormation'
               
                    ])
            ->add('Promotion', TextType::class)
            ->add('nbPlace', IntegerType::class)
            ->add('dateDebut', DateType::class)
            ->add('dateFin', DateType::class)
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nomSalle'
            ])
            ->add('stagiaires', EntityType::class, [
                'class' => Stagiaire::class,
                'attr' =>[
                    'class' => 'selectpicker',
                    'multiple data-live-search'=>"true",
                ],
                'multiple' => true,
                
                
            ])
            ->add('submit', SubmitType::class)
            
            
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}

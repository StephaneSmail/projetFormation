<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Session;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contenir', EntityType::class, [              
                    'class' => Formation::class,
                    'choice_label' => 'nomFormation'
               
                    ])
            ->add('Promotion')
            ->add('nbPlace')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nomSalle'
            ])
            ->add('Prout', SubmitType::class)
            
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}

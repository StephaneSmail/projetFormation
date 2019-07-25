<?php

namespace App\Form;

use App\Entity\Salle;
use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('nbPlace', IntegerType::class, [
                'attr' => ["maxNb" => null ]
                ]
               
            )

            ->add('dateDebut', DateType::Class, array(
                'widget' => 'choice',
                'years' => range(date('1990 '), date('Y') + 10),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ))
            ->add('dateFin', DateType::Class, array(
                'widget' => 'choice',
                'years' => range(date('1990 '), date('Y') + 10),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ))
            ->add('salle', EntityType::class, [
                'class' => Salle::class,
                'choice_label' => 'nomSalle'
            ])
            
            ->add('stagiaires', CollectionType::class, [
                    'entry_type' => EntityType::class,
                   
                    'entry_options' => ['label' => "Choisir stagiaire :", "class" => Stagiaire::class],
                    'allow_add' => true,
                    'allow_delete' => true
                ])
                
                ->add('submit', SubmitType::class, ['label'=>'Valider', 'attr'=>['class'=>'btn-primary btn-block']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}

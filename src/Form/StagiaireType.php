<?php

namespace App\Form;

use App\Entity\Stagiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('dateNaissance', DateType::Class, array(
                'widget' => 'choice',
                'years' => range(date('Y'), date('Y')-100),
                'months' => range(date('m'), 12),
                'days' => range(date('d'), 31),
            ))
            ->add('adresse', TextType::class)
            ->add('ville', TextType::class)
            ->add('cp', TextType::class, array(
                'label' => 'Code Postal',
                'constraints' => new Length([
                    'min' => 4,
                    'minMessage' => "Veuillez mettre plus de {{ limit }} characters",
                    'max' => 12,
                    'maxMessage' => "Veuillez ne pas mettre plus de {{ limit }} characters"
                ]),
            ))
            ->add('email', EmailType::class)
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'M',
                    'Femme' => 'F',
                ]    
            ])
            ->add('telephone', TelType::class,array(
                'constraints' => new Length([
                    'min' => 10,
                    'minMessage' => "Veuillez mettre plus de {{ limit }} characters",
                    'max' => 32,
                    'maxMessage' => "Veuillez ne pas mettre plus de {{ limit }} characters"
                ]),
            
            ))
            ->add('submit', SubmitType::class, ['label'=>'Valider', 'attr'=>['class'=>'btn-primary btn-block']])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}

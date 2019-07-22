<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
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
            ->add('telephone', TelType::class,array(
                'constraints' => new Length([
                    'min' => 10,
                    'minMessage' => "Veuillez mettre plus de {{ limit }} characters",
                    'max' => 32,
                    'maxMessage' => "Veuillez ne pas mettre plus de {{ limit }} characters"
                ]),
            ))
            ->add('email', EmailType::class)
            ->add('photo', UrlType::class)
            ->add('roles', ChoiceType::class, [
                'attr' =>[
                    'class' => 'selectpicker',
                    'multiple data-live-search'=>"true",
                ],
                'choices' => [
                    'user' => 'ROLE_USER',
                    'admin' => 'ROLE_ADMIN',
                    'super admin' => 'ROLE_SUPER_ADMIN'
                ],
                'multiple' => true,   
            ])
            ->add('enseigner', EntityType::class, [
                'class' => Categorie::class,
                'attr' =>[
                    'class' => 'selectpicker',
                    'multiple data-live-search'=>"true",
                ],
                'multiple' => true,
                'choice_label' => 'nomCategorie'
                
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmation du mot de passe'),
                'constraints' => new Length([
                    'min' => 6,
                    'minMessage' => "Veuillez mettre plus de {{ limit }} characters",
                    'max' => 16,
                    'maxMessage' => "Veuillez ne pas mettre plus de {{ limit }} characters"
                ]),
            ))
            ->add('submit', SubmitType::class, ['label'=>'Inscrire', 'attr'=>['class'=>'btn-primary btn-block']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

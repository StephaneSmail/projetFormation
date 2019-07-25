<?php

namespace App\Form;

use App\Entity\Salle;
use App\Form\DureeType;
use App\Entity\Posseder;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Form\PossederType;
use App\Form\MaterielsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MaterielsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('posseders', CollectionType::class, [
                'label' => false,
                'entry_type'=> PossederType::class,
                'entry_options'=>[
                    'label'=>"Matériel et quantité : "
                ],
                'allow_add'=> true,
                'allow_delete'=> true,
                'by_reference' => false

            ])
           
            ->add('submit', SubmitType::class, ['label'=>'Envoyer', 'attr'=>['class'=>'btn-primary btn-block']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
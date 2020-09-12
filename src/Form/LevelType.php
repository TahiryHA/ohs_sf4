<?php

namespace App\Form;

use App\Entity\AcademicYear;
use App\Entity\Level;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LevelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('academicYear',EntityType::class,[
                'class' => AcademicYear::class,
                'choice_label' => 'name',
                'mapped' => false
            ]) 
            // ->add('news')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Level::class,
        ]);
    }
}

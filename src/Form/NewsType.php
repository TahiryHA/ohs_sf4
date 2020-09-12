<?php

namespace App\Form;

use App\Entity\News;
use App\Entity\Level;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('level',EntityType::class,[
                    'label' => 'Règion',
                    'class' => Level::class,
                    'choice_label' => 'name',
                    'placeholder' => 'Veullez séléctionner une classe',
                    'multiple' => true,
                    'expanded' => false
                ])
            ->add('title')
            ->add('imageFile',FileType::class,[
                'label' => false,
                'required' => false,
            ])
            ->add('content',TextareaType::class,[
                'label' => 'Contenu',
                'attr' => ['placeholder' => 'Contenu','class' => 'textarea'],
                'required' => false
            ])
            // ->add('createdAt')
            
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories',EntityType::class,[
                'label' => 'Règion',
                'class' => Categories::class,
                'choice_label' => 'name',
                'placeholder' => 'Veullez séléctionner une règion',
                'multiple' => true,
                'expanded' => false
            ])
            ->add('title')
            ->add('content',TextareaType::class,[
                'label' => 'Contenu',
                'attr' => ['placeholder' => 'Contenu','class' => 'textarea'],
                'required' => false
            ])
            ->add('imageFile',VichFileType::class,[
                'label' => false,
                'required' => false,
                'allow_delete'  => false, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
            ])
            // ->add('createdAt')
            // ->add('likes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}

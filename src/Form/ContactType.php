<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'First Name'
                ]
            ])
            ->add('lastname',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Last Name'
                ]
            ])
            ->add('phone',TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Phone'
                ]
            ])
            ->add('message',TextareaType::class,[
                'attr' => [
                    'cols' => 30,
                    'rows' => 7,
                    'class' => 'form-control',
                    'placeholder' => 'Message'
                ]
            ])
            // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

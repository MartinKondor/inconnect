<?php

namespace App\Form;

use App\Entity\{ Post };

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\{
    TextareaType, FileType,
    SubmitType
};

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Share something ...',
                    'class' => 'form-control mb-1'
                ],
                'label' => false
            ])
            ->add('image', FileType::class, [
                'required' => false
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => [ 'class' => 'btn btn-primary' ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'csrf_protection' => true
        ]);
    }
}

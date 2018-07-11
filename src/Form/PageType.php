<?php

namespace App\Form;

use App\Entity\Page;

use PharIo\Manifest\Email;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\{
    EmailType, TextType,
    TextareaType, FileType,
    PasswordType, DateType,
    SubmitType
};

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('page_name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Name of the page'
                ],
                'label' => false
            ])
            ->add('page_type', TextType::class, [
                'attr' => [
                    'placeholder' => 'Page type like celebrity, business, product ... etc'
                ],
                'label' => false
            ])
            ->add('contact_email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Contact email'
                ],
                'label' => false
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'placeholder' => 'Password for administrators (can be changed later)'
                ],
                'label' => false
            ])
            ->add('contact_address', TextType::class, [
                'attr' => [
                    'placeholder' => 'Address'
                ],
                'label' => false,
                'required' => false
            ])
            ->add('contact_postal_code', TextType::class, [
                'attr' => [
                    'placeholder' => 'Postal code'
                ],
                'label' => false,
                'required' => false
            ])
            ->add('contact_phone', TextType::class, [
                'attr' => [
                    'placeholder' => 'Phone number'
                ],
                'label' => false,
                'required' => false
            ])
            ->add('since_date', DateType::class, [
                'years' => range(date('Y'), date('Y') - 100),
                'label' => 'Birth date of your page entity',
                'required' => false
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => [ 'class' => 'btn btn-primary btn-block btn-lg' ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'csrf_protection' => true
        ]);
    }
}

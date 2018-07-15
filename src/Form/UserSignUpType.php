<?php

namespace App\Form;

use App\Entity\ICUser;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\{
   TextType, EmailType,
   PasswordType, DateType,
   ChoiceType, CheckboxType,
   SubmitType
};

class UserSignUpType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('first_name', TextType::class, [
                  'attr' => [
                        'placeholder' => 'First name'
                  ],
                  'label' => false
            ])
            ->add('last_name', TextType::class, [
                  'attr' => [
                        'placeholder' => 'Last name'
                  ],
                  'label' => false
            ])
            ->add('email', EmailType::class, [
                  'attr' => [
                        'placeholder' => 'Email'
                  ],
                  'label' => false
            ])
            ->add('plain_password', PasswordType::class, [
                  'attr' => [
                        'placeholder' => 'Password'
                  ],
                  'label' => false
            ])
            ->add('birth_date', DateType::class, [
                  'years' => range(date('Y'), date('Y') - 100)
            ])
            ->add('gender', ChoiceType::class, [
                  'choices' => [ 'female' => 'Female', 'male' => 'Male' ],
                  'label' => false
            ])
            ->add('termsBox', CheckboxType::class, [
                  'mapped' => false,
                  'label' => 'I accept the',
                  'constraints' => new IsTrue(),
            ])
            ->add('Submit', SubmitType::class, [
                  'attr' => [ 'class' => 'btn btn-primary btn-lg btn-block mb-1 mt-2' ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ICUser::class,
            'csrf_protection' => true
        ]);
    }
}

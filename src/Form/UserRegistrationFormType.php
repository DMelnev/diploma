<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => '* Enter E-mail address:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'E-mail address'
                ]
            ])
            ->add('name', TextType::class, [
                'label' => '* Enter your name:',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Name',
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords must be equal!',
                'options' => ['attr' => ['class' => 'form-input'],
                    'label_attr' => ['class' => 'form-label']],
                'required' => true,
                'first_options'  => ['label' => '* Enter password:'],
                'second_options' => ['label' => '* Repeat Password:'],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Consent to the processing of personal data',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationFormModel::class,
        ]);
    }
}

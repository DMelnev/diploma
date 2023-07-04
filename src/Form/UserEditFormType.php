<?php

namespace App\Form;

use App\Form\Model\UserEditFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $imageConstrains = [
            new Image([
                'maxSize' => '5M',
//                'minWidth' => 480,
//                'minHeight' => 300,
            ])
        ];
        $builder
            ->add('name', null, [
                'label' => 'Enter your name:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Name',
                    'pattern' => false,
                    'maxlength' => false,
                ]])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Passwords must be equal!',
//                'error_bubbling'=>true,
                'options' => [
                    'attr' => ['class' => 'form-input'],
                    'row_attr'=>['class' => 'form-group'],
                    'label_attr'=>['class' => 'form-label'],
                    ],
                'required' => false,
                'first_options' => [
                    'label' => 'Введите новый пароль',
                    ],
                'second_options' => ['label' => 'Подтверждение пароля',],
            ])

            ->add('email', EmailType::class, [
                'label' => 'Enter E-mail address:',
                'required' => false,
                'attr' => [
                    'placeholder' => 'E-mail address'
                ]
            ])
            ->add('phone', TelType::class, [
                'required'=>false,
                'attr' => [
                    'placeholder' => '+70000000000'
                ]
            ])
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label'=>'Выберите аватар',
                'constraints' => $imageConstrains,
                'attr' => [
                    'class' => 'Profile-file form-input',
                ],
                'label_attr'=>[
                    'class'=>'Profile-fileLabel',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserEditFormModel::class,
        ]);
    }
}

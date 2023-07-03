<?php

namespace App\Form;

use App\Entity\Feedback;
use App\Form\Model\FeedbackFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text',TextareaType::class, [
                'label'=>false,
                'required'=>true,
                'attr'=>[
                    'class'=>'form-textarea',
                    'placeholder'=>'Your comment...'
                ],
            ] )
            ->add('email', EmailType::class, [
                'label'=> false,
                'required' => true,
                'attr'=>[
                    'placeholder'=>'Your E-mail address',
                    'class' => 'form-input',
                ]
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Your name',
                    'class' => 'form-input',
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FeedbackFormModel::class,
        ]);
    }
}

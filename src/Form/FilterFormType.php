<?php


namespace App\Form;

use App\Entity\User;
use App\Form\Model\FilterFormModel;
use App\Repository\ProductPropertyRepository;
use App\Repository\UserRepository;
use App\Service\RolesConst;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType implements RolesConst
{
    private UserRepository $userRepository;
    private ProductPropertyRepository $ppRepository;

    /**
     * FilterFormType constructor.
     */
    public function __construct(UserRepository $userRepository, ProductPropertyRepository $ppRepository)
    {
        $this->userRepository = $userRepository;
        $this->ppRepository = $ppRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('price', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'range-line',
                    'id' => 'price',
                    'data-type' => 'double',
                    'data-min' => '7',
                    'data-max' => '50',
                    'data-from' => '7',
                    'data-to' => '27'
                ]
            ])
            ->add('title', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-input form-input_full',
                    'placeholder' => 'Название',
                ],
            ])
            ->add('seller', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getName();
                },
                'required' => false,
                'placeholder' => 'Продавец',
                'choices' => $this->userRepository->findAllSellers(),
                'attr' => [
                    'class' => 'form-select',
                ]
            ])
            ->add('brokenScreen', CheckboxType::class, [
                'required' => false,
            ])
            ->add('memoryValue', ChoiceType::class, [
                'required' => false,
                'placeholder' => 'Объем памяти',
                'choice_attr' => [
                    'placeholder' => ['disabled' => true],
                ],
                'choices' => $this->ppRepository->propertiesGroup('Объем памяти'),
                'attr' => [
                    'class' => 'form-select',
                    'size' => 4,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FilterFormModel::class,
        ]);
    }
}
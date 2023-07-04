<?php


namespace App\Form;

use App\Entity\User;
use App\Form\Model\FilterFormModel;
use App\Repository\ProductPropertyRepository;
use App\Repository\UserRepository;
use App\Service\RolesConst;
use App\Service\SortConst;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType implements RolesConst, SortConst
{
    private UserRepository $userRepository;
    private ProductPropertyRepository $ppRepository;

    /**
     * FilterFormType constructor.
     */
    public function __construct(
        UserRepository $userRepository,
        ProductPropertyRepository $ppRepository)
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
                    'data-min' => $options[self::FILTER_MIN_PRICE],
                    'data-max' => $options[self::FILTER_MAX_PRICE],
                    'data-from' => $options[self::FILTER_FROM_PRICE],
                    'data-to' => $options[self::FILTER_TO_PRICE],
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
                'placeholder' => self::FILTER_TEMP,
                'choice_attr' => [
                    'placeholder' => ['disabled' => true],
                ],
                'choices' => $this->ppRepository->propertiesGroup(self::FILTER_TEMP),
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
        $resolver->setRequired([
            self::FILTER_MIN_PRICE,
            self::FILTER_MAX_PRICE,
            self::FILTER_FROM_PRICE,
            self::FILTER_TO_PRICE,
        ]);
    }
}
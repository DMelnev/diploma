<?php


namespace App\Service;


use App\Entity\User;
use App\Form\Model\UserEditFormModel;
use App\Repository\CartRepository;
use App\Repository\PaySystemRepository;
use App\Repository\SectionRepository;
use App\Repository\ShowHistoryRepository;
use App\Repository\SocialRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CabinetService extends MainBaseService
{
    private ParameterBagInterface $parameterBag;
    private ShowHistoryRepository $showHistoryRepository;
    private CartRepository $cartRepository;
    private MyFiles $uploader;
    private UserPasswordHasherInterface $passwordHash;
    private EntityManagerInterface $entityManager;

    public function __construct(
        SocialRepository $socialRepository,
        PaySystemRepository $paySystemRepository,
        SectionRepository $sectionRepository,
        ParameterBagInterface $parameterBag,
        ShowHistoryRepository $showHistoryRepository,
        CartRepository $cartRepository,
        MyFiles $uploader,
        UserPasswordHasherInterface $passwordHash,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($socialRepository, $paySystemRepository, $sectionRepository);
        $this->parameterBag = $parameterBag;
        $this->showHistoryRepository = $showHistoryRepository;
        $this->cartRepository = $cartRepository;
        $this->uploader = $uploader;
        $this->passwordHash = $passwordHash;
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function getIndex(User $user): ?array
    {
        $result = $this->getAllBase();
        $result['cart'] = $this->cartRepository->getLast($user);
        $result['history'] = $this->showHistoryRepository->getLast($user, $this->parameterBag->get('user.show_prehistory'));
        return $result;
    }

    /**
     * @param FormInterface $form
     * @param User $user
     * @return FormInterface
     * @throws FilesystemException
     */
    public function getProfile(FormInterface $form, User $user): FormInterface
    {
        /** @var UserEditFormModel $userModel */
        $userModel = $form->getData();
        $image = $form->get('avatar')->getData();
        if ($image) {
            $filename = $this->uploader->uploadDeleteFile($image, $user->getAvatar());
            $user->setAvatar($filename);
        }
        $user
            ->setName($userModel->getName())
            ->setPhone($userModel->getPhone()
            );
        if ($userModel->getPlainPassword()) {
            $user->setPassword($this->passwordHash->hashPassword(
                $user,
                $userModel->getPlainPassword()
            ));
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $form;
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function getView(User $user): ?array
    {
        $result = $this->getAllBase();
        $result['history'] = $this->showHistoryRepository->getLast($user, $this->parameterBag->get('user.show_history'));
        return $result;
    }

    /**
     * @param User $user
     * @return array|null
     */
    public function getOrders(User $user): ?array
    {
        $result = $this->getAllBase();
        $result['orders'] = $this->cartRepository->getAll($user, $this->parameterBag->get('user.cart_history'));
        return $result;
    }
}
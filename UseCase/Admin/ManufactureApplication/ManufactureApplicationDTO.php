<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\ManufactureApplication;

use BaksDev\Manufacture\Part\Application\Repository\ActionByMain\ActionByMainInterface;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\ManufactureApplicationProductsDTO;
use BaksDev\Manufacture\Part\UseCase\Admin\AddProduct\ManufacturePartProductsDTO;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use BaksDev\Users\UsersTable\Type\Actions\Event\UsersTableActionsEventUid;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/** @see ManufactureApplicationEvent */
final class ManufactureApplicationDTO /*implements ManufactureApplicationEventInterface */
{

    /**
     * Идентификатор события
     */
    #[Assert\Uuid]
    private ?ManufactureApplicationEventUid $id = null;

    /**
     * Профиль ответственного.
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private UserProfileUid $fixed;

    /**
     * Приоритет
     */
    private bool $priority = false;

    /**
     * Идентификатор события
     */
    public function getEvent(): ?ManufactureApplicationEventUid
    {
        return $this->id;
    }

    public function setId(ManufactureApplicationEventUid $eventUid): self
    {
        $this->id = $eventUid;

        return $this;
    }


    /**
     * Идентификатор процесса производства
     */
//    private UsersTableActionsEventUid $action;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private UsersTableActionsEventUid $action;

    private ArrayCollection $application_product_form_data;

    public function __construct(UserProfileUid $profile, ActionByMainInterface $actionByMain)
    {
        $this->application_product_form_data = new ArrayCollection();
        $this->fixed = $profile;

        $action = $actionByMain->findUsersTableActionByMain(new ManufactureApplicationUid(ManufactureApplicationUid::ACTION_ID) );
        $this->action = $action;

    }

    public function getApplicationProductFormData(): ArrayCollection
    {
        return $this->application_product_form_data;
    }

    public function addApplicationProductFormData(ManufactureApplicationProductsDTO $product): self
    {
//        $product->setProfile($this->profile);

        $this->application_product_form_data->add($product);

        return $this;
    }

    public function removeApplicationProductFormData(ManufactureApplicationProductsDTO $product): self
    {
        $this->application_product_form_data->removeElement($product);
        return $this;
    }

    /**
     * Fixed
     */
    public function getFixed(): UserProfileUid
    {
        return $this->fixed;
    }

    public function setFixed(UserProfileUid $fixed): self
    {
        $this->fixed = $fixed;
        return $this;
    }

    /**
     * Action
     */
    public function getAction(): UsersTableActionsEventUid
    {
        return $this->action;
    }

    public function setAction(UsersTableActionsEventUid $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function getPriority(): bool
    {
        return $this->priority;
    }

    public function setPriority(bool $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

}
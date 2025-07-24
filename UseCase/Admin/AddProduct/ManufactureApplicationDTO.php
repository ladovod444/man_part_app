<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct;

use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEventInterface;
use BaksDev\Manufacture\Part\Application\Repository\ActionByMain\ActionByMainInterface;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\ManufactureApplicationStatusNew;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\Product\ManufactureApplicationProductDTO;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use BaksDev\Users\UsersTable\Type\Actions\Event\UsersTableActionsEventUid;
use Symfony\Component\Validator\Constraints as Assert;

/** @see ManufactureApplicationEvent */
final class ManufactureApplicationDTO implements ManufactureApplicationEventInterface
{

    /**
     * Идентификатор события
     */
    //#[Assert\isNull]
    private readonly null $id;


    /**
     * Идентификатор процесса производства
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    private readonly UsersTableActionsEventUid $action;

    #[Assert\NotBlank]
    #[Assert\Uuid]
    private UserProfileUid $fixed;

    private bool $priority;

    private ManufactureApplicationProductDTO $product;

    public function setProduct(ManufactureApplicationProductDTO $product): self
    {
        $this->product = $product;
        return $this;
    }

    /** Статус заявки */
    #[Assert\NotBlank]
    private readonly ManufactureApplicationStatus $status;


    public function __construct(UserProfileUid $profile,) {

        $this->id = null;
        $this->product = new ManufactureApplicationProductDTO();
        $this->fixed = $profile;

        $this->action = new UsersTableActionsEventUid(ManufactureApplicationUid::ACTION_ID);
        $this->status = new ManufactureApplicationStatus(ManufactureApplicationStatusNew::class);

    }

    /**
     * Идентификатор события
     */
    public function getEvent(): null
    {
        return $this->id;
    }


    public function getProduct(): ManufactureApplicationProductDTO
    {
        return $this->product;
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

    public function getAction(): UsersTableActionsEventUid
    {
        return $this->action;
    }

    public function getFixed(): UserProfileUid
    {
        return $this->fixed;
    }

    public function getStatus(): ManufactureApplicationStatus
    {
        return $this->status;
    }

}
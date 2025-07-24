<?php

namespace BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct;

use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEventInterface;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\ManufactureApplicationStatusNew;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\AddProduct\Product\ManufactureApplicationProductDTO;
use BaksDev\Manufacture\Part\Application\UseCase\Admin\UpdateManufactureApplicationProduct\Product\UpdateManufactureApplicationProductDTO;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use BaksDev\Users\UsersTable\Type\Actions\Event\UsersTableActionsEventUid;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateManufactureApplicationDTO implements ManufactureApplicationEventInterface
{
    /**
     * Идентификатор события
     */
    #[Assert\Uuid]
    private ?ManufactureApplicationEventUid $id;

//    private ManufactureApplicationProductDTO $product;
    private UpdateManufactureApplicationProductDTO $product;

    public function setProduct(UpdateManufactureApplicationProductDTO $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function __construct() {

//        $this->id = null;
        $this->product = new UpdateManufactureApplicationProductDTO();
//        $this->fixed = $profile;
//
//        $this->action = new UsersTableActionsEventUid(ManufactureApplicationUid::ACTION_ID);
//        $this->status = new ManufactureApplicationStatus(ManufactureApplicationStatusNew::class);

//        $this->product = $product;
    }

    /**
     * Идентификатор события
     */
    public function getEvent(): ?ManufactureApplicationEventUid
    {
        return $this->id;
    }

    public function setId(?ManufactureApplicationEventUid $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getProduct(): UpdateManufactureApplicationProductDTO
    {
        return $this->product;
    }

}
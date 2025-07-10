<?php

namespace BaksDev\Manufacture\Part\Application\Entity\Event;

use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Entity\Product\ManufactureApplicationProduct;
use BaksDev\Manufacture\Part\Application\Repository\ActionByMain\ActionByMainInterface;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;

use BaksDev\Manufacture\Part\Entity\Products\ManufacturePartProduct;
use BaksDev\Users\Profile\UserProfile\Type\Id\UserProfileUid;
use BaksDev\Users\UsersTable\Type\Actions\Event\UsersTableActionsEventUid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'manufacture_application_event')]
#[ORM\Index(columns: ['action'])]
//#[ORM\Index(columns: ['status'])]
#[ORM\Index(columns: ['fixed'])]
class ManufactureApplicationEvent extends EntityEvent
{

    /**
     * Идентификатор События
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: ManufactureApplicationEventUid::TYPE)]
    private ManufactureApplicationEventUid $id;

    /**
     * Идентификатор ManufactureApplication
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: ManufactureApplicationUid::TYPE, nullable: false)]
    private ?ManufactureApplicationUid $main = null;

    // TODO добавлять поля

    /**
     * Идентификатор процесса производства
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: UsersTableActionsEventUid::TYPE)]
    private UsersTableActionsEventUid $action;

//    public function setAction(UsersTableActionsEventUid $action): self
//    {
//        $this->action = $action;
//        return $this;
//    }


//    public function setAction(ActionByMainInterface $actionByMain): self
//    {
////        $this->action = $action;
//
//        $action_id = $actionByMain->findUsersTableActionByMain(new ManufactureApplicationUid(ManufactureApplicationUid::ACTION_ID));
//        $this->action = new UsersTableActionsEventUid($action_id);
//
//        return $this;
//    }


    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: UserProfileUid::TYPE,)]
    private UserProfileUid $fixed;


    #[ORM\Column(type: 'boolean', nullable: true)]
    private bool $priority;

    public function setPriority(bool $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function isPriority(): bool
    {
        return $this->priority;
    }

    /**
     * Коллекция продукции
     */
    #[Assert\Valid]
    #[ORM\OneToOne(targetEntity: ManufactureApplicationProduct::class, mappedBy: 'event', cascade: ['all'], fetch: 'EAGER')]
    private Collection $product;

    public function setProduct(Collection $product): self
    {
        $this->product = $product;
        return $this;
    }


    public function __construct() {
        $this->id = new ManufactureApplicationEventUid();

//        $action_id = $actionByMain->findUsersTableActionByMain(new ManufactureApplicationUid(ManufactureApplicationUid::ACTION_ID));
//        $this->action = new UsersTableActionsEventUid($action_id);
//        $this->setAction(ActionByMainInterface $actionByMain);
//        $this->action = new UsersTableActionsEventUid(ManufactureApplicationUid::ACTION_ID);
    }

    public function __clone()
    {
        $this->id = clone $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function getId(): ManufactureApplicationEventUid
    {
        return $this->id;
    }

    public function setId(ManufactureApplicationEventUid $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getMain(): ?ManufactureApplicationUid
    {
        return $this->main;
    }

    public function setMain(ManufactureApplicationUid|ManufactureApplication $main): self
    {
        $this->main = $main instanceof ManufactureApplication ? $main->getId() : $main;

        return $this;
    }


    /**
     * Product
     * @return Collection{ int, ManufactureApplicationProduct }
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

}
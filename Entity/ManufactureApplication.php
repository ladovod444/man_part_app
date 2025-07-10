<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\Entity;

use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;
use BaksDev\Manufacture\Part\Application\Type\Id\ManufactureApplicationUid;
use BaksDev\Manufacture\Part\Application\Entity\Event\ManufactureApplicationEvent;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;


/* ManufactureApplication */

#[ORM\Entity]
#[ORM\Table(name: 'manufacture_application')]
class ManufactureApplication
{
    /**
     * Идентификатор сущности
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\Column(type: ManufactureApplicationUid::TYPE)]
    private ManufactureApplicationUid $id;

    /**
     * Идентификатор события
     */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Column(type: ManufactureApplicationEventUid::TYPE, unique: true)]
    private ManufactureApplicationEventUid $event;

    public function __construct()
    {
        $this->id = new ManufactureApplicationUid();
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    /**
     * Идентификатор
     */
    public function getId(): ManufactureApplicationUid
    {
        return $this->id;
    }

    public function setId(ManufactureApplicationUid $id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * Идентификатор события
     */
//    public function getEvent(): ManufactureApplicationEventUid
    public function getEvent(): ManufactureApplicationEventUid
    {
        return $this->event;
    }

    public function setEvent(ManufactureApplicationEventUid|ManufactureApplicationEvent $event): void
    {
        $this->event = $event instanceof ManufactureApplicationEvent ? $event->getId() : $event;
    }
}
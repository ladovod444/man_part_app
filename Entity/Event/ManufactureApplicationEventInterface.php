<?php

namespace BaksDev\Manufacture\Part\Application\Entity\Event;

use BaksDev\Manufacture\Part\Application\Entity\ManufactureApplication;
use BaksDev\Manufacture\Part\Application\Type\Event\ManufactureApplicationEventUid;

interface ManufactureApplicationEventInterface
{
    public function getEvent(): ?ManufactureApplicationEventUid;
}
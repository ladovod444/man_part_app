<?php

namespace BaksDev\Manufacture\Part\Application\Type\Event;

use BaksDev\Core\Type\UidType\UidType;

class ManufactureApplicationEventType extends UidType
{
    public function getClassType(): string
    {
        return ManufactureApplicationEventUid::class;
    }

    public function getName(): string
    {
        return ManufactureApplicationEventUid::TYPE;
    }
}
<?php

declare(strict_types=1);

namespace BaksDev\Manufacture\Part\Application\Type\Id;


use BaksDev\Core\Type\UidType\UidType;

final class ManufactureApplicationType extends UidType
{
    public function getClassType(): string
    {
        return ManufactureApplicationUid::class;
    }

    public function getName(): string
    {
        return ManufactureApplicationUid::TYPE;
    }

    public function getActionName(): string
    {
        return ManufactureApplicationUid::ACTION_NAME;
    }
}
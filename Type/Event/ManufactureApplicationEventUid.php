<?php

namespace BaksDev\Manufacture\Part\Application\Type\Event;

use BaksDev\Core\Type\UidType\Uid;
use Symfony\Component\Uid\AbstractUid;

class ManufactureApplicationEventUid extends Uid
{
    public const string TEST = '0197d561-1fcf-7c75-8dd3-d2c580e004ab';

    public const string TYPE = 'manufacture_application_event';

    private mixed $attr;

    private mixed $option;

    private mixed $property;

    private mixed $characteristic;

    public function __construct(
        AbstractUid|self|string|null $value = null,
        mixed $attr = null,
        mixed $option = null,
        mixed $property = null,
        mixed $characteristic = null,
    )
    {
        parent::__construct($value);

        $this->attr = $attr;
        $this->option = $option;
        $this->property = $property;
        $this->characteristic = $characteristic;
    }


    public function getAttr(): mixed
    {
        return $this->attr;
    }


    public function getOption(): mixed
    {
        return $this->option;
    }


    public function getProperty(): mixed
    {
        return $this->property;
    }

    public function getCharacteristic(): mixed
    {
        return $this->characteristic;
    }
}
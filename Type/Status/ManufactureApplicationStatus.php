<?php

namespace BaksDev\Manufacture\Part\Application\Type\Status;

use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\Collection\ManufactureApplicationStatusInterface;
use BaksDev\Manufacture\Part\Application\Type\Status\ManufactureApplicationStatus\ManufactureApplicationStatusNew;
use InvalidArgumentException;

final class ManufactureApplicationStatus
{
    public const string TYPE = 'manufacture_application_status_type';
    public const string TEST = ManufactureApplicationStatusNew::class;

    // TODO
    // TEST

    private ManufactureApplicationStatusInterface $status;

    public function __construct(self|string|ManufactureApplicationStatusInterface $status) {
//        $this->status = $status;

        if(is_string($status) && class_exists($status))
        {
            $instance = new $status();

            if($instance instanceof ManufactureApplicationStatusInterface)
            {
                $this->status = $instance;
                return;
            }
        }

        if($status instanceof ManufactureApplicationStatusInterface)
        {
            $this->status = $status;
            return;
        }

        if($status instanceof self)
        {
            $this->status = $status->getManufactureApplicationStatus();
            return;
        }

        /** @var ManufactureApplicationStatusInterface $declare */
        foreach(self::getDeclared() as $declare)
        {
            $instance = new self($declare);

            if($instance->getManufactureApplicationStatusValue() === $status)
            {
                $this->status = new $declare();
                return;
            }
        }

        throw new InvalidArgumentException(sprintf('Not found ManufactureApplicationStatus %s', $status));

    }

    public function __toString(): string
    {
        return $this->status->getValue();
    }

    public function getManufactureApplicationStatus(): ManufactureApplicationStatusInterface
    {
        return $this->status;
    }

    public function getManufactureApplicationStatusValue(): string {
        return $this->status->getValue();
    }

    public static function cases(): array
    {
        $case = [];

        foreach(self::getDeclared() as $key => $declared)
        {
            /** @var ManufactureApplicationStatusInterface $declared */
            $class = new $declared();

            $case[$class::priority().$key] = new self($class);
        }

        ksort($case);

        return $case;
    }

    public static function getDeclared(): array
    {
        return array_filter(
            get_declared_classes(),
            static function($className) {
                return in_array(ManufactureApplicationStatusInterface::class, class_implements($className), true);
            }
        );
    }

    public function equals(mixed $status): bool
    {
        $status = new self($status);
        return $this->getManufactureApplicationStatusValue() === $status->getManufactureApplicationStatusValue();
    }

}